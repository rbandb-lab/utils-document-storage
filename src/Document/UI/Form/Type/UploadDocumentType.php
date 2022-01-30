<?php

declare(strict_types=1);

namespace App\Document\UI\Form\Type;

use App\Document\Domain\Dto\DocumentUploadDto;
use App\Document\Domain\Repository\CfgRepositoryInterface;
use App\Document\Domain\Repository\StorageRepositoryInterface;
use App\Document\UI\Form\Transformer\CfgNameToTypeTransformer;
use App\Document\UI\Form\Transformer\StorageNameToStorageTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UploadDocumentType extends AbstractType
{
    private CfgRepositoryInterface $cfgRepository;
    private CfgNameToTypeTransformer $configTransformer;
    private StorageNameToStorageTransformer $storageTransformer;
    private StorageRepositoryInterface $storageRepository;

    public function __construct(
        CfgRepositoryInterface $cfgRepository,
        CfgNameToTypeTransformer $configTransformer,
        StorageRepositoryInterface $storageRepository,
        StorageNameToStorageTransformer $storageTransformer
    ) {
        $this->cfgRepository = $cfgRepository;
        $this->storageRepository = $storageRepository;
        $this->storageTransformer = $storageTransformer;
        $this->configTransformer = $configTransformer;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'data_class' => DocumentUploadDto::class,
                'csrf_protection' => false,
            ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $configs = $this->cfgRepository->findAll();
        $buckets = $this->storageRepository->findAll();

        $types = [];
        foreach ($configs as $config) {
            $types[] = strtolower($config->getName());
        }
        if (empty($types)) {
            throw new BadRequestHttpException('The CfgDocumentType table does not contains any type');
        }

        $storages = [];
        foreach ($buckets as $bucket) {
            $storages[] = strtolower($bucket->getName());
        }

        if (empty($storages)) {
            throw new BadRequestHttpException('The CfgStorageBucket table does not contains any type');
        }

        $builder
            ->add('file', FileType::class, [
                'required' => false,
                'constraints' => new NotBlank(),
            ])
            ->add('name', TextType::class, [
                'required' => false,
            ])
            ->add('directory', TextType::class, [
                'required' => false,
            ])
        ;

        $builder->add('documentType', TextType::class, [
            'required' => false,
//            'constraints' => new NotBlank(),
        ]);

//        if($builder->get('documentType')->getData()) {
        $builder->get('documentType')->addModelTransformer($this->configTransformer);
//        }

        $builder->add('storage', TextType::class, [
                'required' => false,
                'constraints' => new NotBlank(),
        ]);

        $builder->get('storage')->addModelTransformer($this->storageTransformer);
    }
}
