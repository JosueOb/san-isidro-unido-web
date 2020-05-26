const Swal = require('sweetalert2')

let numberOfDocumentAllowed = 1;
let size = 5242880;//equivale a 5MB (bytes)

var oldDocuments = [];
var newDocuments = [];
var documentsRender = [];

const previewDocument = arrayDocument => {
    let documentItem = '';
    var numberOfOldDocuments = 0;
    var numberOfNewDocuments = 0;

    arrayDocument.forEach(function(document, index){
        for(var group in document){
            document[group]['position'] = index;

            if(group === 'new'){
                document[group]['index'] = numberOfNewDocuments++;
                documentItem += `
                    <div class='gallery-item'>
                        <i class="fas fa-file-pdf image-document"></i>
                        <p class="document-name">${document[group]['name']}</p>
                        <i class="fas fa-trash-alt image-cancel" id="delete_${group}_document" data-position="${document[group]['position']}" data-index="${document[group]['index']}"></i>
                    </div>
                    `;
            }
            if(group === 'old'){
                document[group]['index'] = numberOfOldDocuments++;
                documentItem += `
                <div class="gallery-item">
                    <i class="fas fa-file-pdf image-document"></i>
                    <a href="${document[group]['src']}" class="link-document" target="_blank">
                        <i class="fas fa-eye"></i>
                    </a>
                    <i class="fas fa-trash-alt image-cancel" id="delete_${group}_document" data-position="${document[group]['position']}" data-index="${document[group]['index']}"></i>

                </div>`;
            };
        }
    });

    if ($('#gallery-documents').length != 0) {
        $('#gallery-documents').html(documentItem);
    }
}


$('#documents').on('change', function(event){
    $('#documents').removeClass('is-invalid');

     //Se obtiene los documentos del input
     var files = event.target.files;
     //se verifica que se haya seleccionado algún documento
     if (files) {
        //se recorre cada archivo para verificar que sea un PDF
        [].forEach.call(files, function (file, index) {

            if (/\.(pdf)$/i.test(file.name)) {
                //Si el documento es menor a 5MB
                if (file.size < size) {
                    if (documentsRender.length < numberOfDocumentAllowed) {
                        newDocuments.push(file);

                        var documentRender = new Array();
                        var documents = new Array();

                        documentRender['name'] = file.name;
                        documents['new'] = documentRender;

                        documentsRender.push(documents);

                        previewDocument(documentsRender);
                        console.log(documentsRender.length)
                    } else {
                        Swal.fire({
                            type: 'error',
                            title: 'Fuera del límite de documentos seleccionados',
                            text: 'Recuerda que solo puedes seleccionar hasta '+numberOfDocumentAllowed+' documento(s)',
                        })
                    }

                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Fuera del límite de '+ ( size / 1048576) +'MB',
                        text: 'El documento ' + file.name + ' pesa ' + (file.size / 1048576).toFixed(2) + 'MB',
                    })
                }
            } else {
                console.log('Archivo no permitidos');
                $('#documents').addClass('is-invalid');
                $('#documents').siblings('.invalid-feedback').html('<strong> Archivo no permitido </strong>');
            }
        });
    }
});

$('#gallery-documents').on('click', '#delete_old_document', function () {
    let documentIndex = $(this).data('index');
    let documentPosition = $(this).data('position');
    oldDocuments.splice(documentIndex, 1);
    documentsRender.splice(documentPosition, 1);
    previewDocument(documentsRender);
});

$('#gallery-documents').on('click', '#delete_new_document', function () {
    let documentIndex = $(this).data('index');
    let documentPosition = $(this).data('position');
    newDocuments.splice(documentIndex, 1);
    documentsRender.splice(documentPosition, 1);
    previewDocument(documentsRender);
});

function resetNumberOfDocumentAllowed(number){
    numberOfDocumentAllowed = number;
}
function resetDocuments(){
    //Se realiza la lectura de las imagenes que que encuentren en la sección de gallería
    var getDocuments = document.querySelectorAll("#gallery-documents .gallery-item a");
    // console.log(getDocuments);

    getDocuments.forEach(function (resource, index) {
        // console.log(resource.href)
        var documentRender = new Array();
        var documents = new Array();

        documentRender['src'] = resource.href;
        documents['old'] = documentRender;

        documentsRender.push(documents);
        oldDocuments.push(resource.dataset.document);
    });

    previewDocument(documentsRender);
}

export{newDocuments, resetNumberOfDocumentAllowed, resetDocuments, oldDocuments}