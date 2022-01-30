### Service XXX-document ###

#### ROUTES ####
create_document:<br>
&nbsp;&nbsp;&nbsp;&nbsp;path: /api/document<br>
&nbsp;&nbsp;&nbsp;&nbsp;methods: POST<br>
&nbsp;&nbsp;&nbsp;&nbsp;controller: App\Document\UI\Action\CreateDocumentAction<br>

get_document:<br>
&nbsp;&nbsp;&nbsp;&nbsp;path: /api/document/{id}<br>
&nbsp;&nbsp;&nbsp;&nbsp;methods: GET<br>
&nbsp;&nbsp;&nbsp;&nbsp;controller: App\Document\UI\Action\GetFileAction<br>

get_document_info:<br>
&nbsp;&nbsp;&nbsp;&nbsp;path: /api/document/{id}/info<br>
&nbsp;&nbsp;&nbsp;&nbsp;methods: GET<br>
&nbsp;&nbsp;&nbsp;&nbsp;controller: App\Document\UI\Action\GetDocumentAction<br>

delete_document:<br>
&nbsp;&nbsp;&nbsp;&nbsp;path: /api/document/{id}<br>
&nbsp;&nbsp;&nbsp;&nbsp;methods: DELETE<br>
&nbsp;&nbsp;&nbsp;&nbsp;controller: App\Document\UI\Action\DeleteDocumentAction<br>

get_documents_info:<br>
&nbsp;&nbsp;&nbsp;&nbsp;path: /api/documents<br>
&nbsp;&nbsp;&nbsp;&nbsp;methods: GET<br>
&nbsp;&nbsp;&nbsp;&nbsp;controller: App\Document\UI\Action\GetDocumentsAction<br>


#### Envoi de fichier au service document :
  POST sur l'endpoint http://service.url/api/document
  form multipart-enc avec les clés :<br>
    - file: [fichier joint]<br>
    - name: [nom du fichier]<br>
    - documentType: à créer dans l'entité CfgDocumentTYpe. Ex : standard_pdf<br>
    - storage : nom du Storage (e.g. Azure, ou GCP, AWS, ...)<br>
    - directory: ex "/myDirectory"<br>

    La réponse renvoie immédiatement l'id du document, qui est dispo immédiatement.

#### Concepts ####
Utilisation de SF Messenger en async<br>
<br>
POST :
- Les fichiers sont stockés sur le container et immédiatement dispos dans /public
- Un évènement est envoyé dans le bus d'events pour déclencher l'upload vers le cloud
- A la suite un nouvel évènement déclenche la suppression du directory /public

<br>
GET :<br>
- recherche sur /public : utile pour les fichiers de grande taille
- cherche sur le cache Redis si la data est présente
- si la data est pas en cache, fetch Azure -> cache Redis<br>

<br>
<br>
DELETE :<br>
- Efface sur Azure, et /public, expire le cache
<br>
<br>
<br>
