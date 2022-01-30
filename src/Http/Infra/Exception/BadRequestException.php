<?php

declare(strict_types=1);

namespace App\Http\Infra\Exception;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

final class BadRequestException extends BadRequestHttpException
{
    final public static function createFromMessage(string $message)
    {
        return new static(json_encode($message));
    }

    final public static function createFromErrors(array $errors)
    {
        $message = [];

        foreach ($errors as $error) {
            /* @var \Symfony\Component\Validator\ConstraintViolationInterface $error */
            $message[$error->getPropertyPath()] = $error->getMessage();
        }

        return new static(json_encode($message));
    }

    final public static function createFromViolations(ConstraintViolationList $list)
    {
        $message = [];

        /** @var ConstraintViolation $violation */
        foreach ($list->getIterator() as $violation) {
            /* @var \Symfony\Component\Validator\ConstraintViolationInterface $violation */
            $message[
            $violation->getInvalidValue()
            ] = $violation->getMessage();
        }

        return new static(json_encode($message));
    }

    final public static function createFromException(\Exception $exception)
    {
        return new static(json_encode($exception->getMessage()));
    }

    final public static function createFromForm(FormInterface $form)
    {
        $message = [];

        foreach (iterator_to_array($form->getErrors(true, true)) as $error) {
            /* @var FormError $error */
            $ancestry = self::getAncestry($form = $error->getOrigin());
            $ancestry[] = $form->getName();

            $path = array_shift($ancestry);

            foreach ($ancestry as $parent) {
                $path .= "[{$parent}]";
            }

            $message[$path] = $error->getMessage();
        }

        return new static(json_encode($message));
    }

    private static function getAncestry(FormInterface $form, array $ancestry = [])
    {
        $parent = $form->getParent();

        if (null !== $parent) {
            $formName = $parent->getName();

            if ('' !== $formName) {
                $ancestry = self::getAncestry($parent, $ancestry);
                $ancestry[] = $formName;
            }
        }

        return $ancestry;
    }
}
