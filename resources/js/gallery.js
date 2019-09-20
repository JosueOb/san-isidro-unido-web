$(document).ready(function(){
    console.log('Formulario listo');
    let images = [];
    let renderImages = [];

    

    const previewImages = images =>{
        let imageItem = '';
        let numberOfImages = 0;
        images.forEach(function(image, indice){
            imageItem += `
            <div class="gallery-item item-1">
                <div class="image-cancel" data-no="${indice}"><i class="fas fa-trash-alt"></i></div>
                <img src=${image} alt='image_${indice}'>
            </div>
            `;
            numberOfImages++;
        });
        document.getElementById('gallery').innerHTML = imageItem;
        var message = images.length > 0 ? 'Imágenes seleccionadas: '+ numberOfImages : 'Seleccione alguna imagen';
        $('#images').siblings('.custom-file-label').addClass('selected').html(message);
    }

    //Al seleccionar el input file
    $('#images').on('change', function(event){
        //Se obtiene las imagenes del input
        var files = event.target.files;
        //se verifica que se haya seleccionado alguna imágen
        if(files){
            console.log('Seleccionó una imagen');
            //se recorre cada archivo para verificar que sea una imágen
            [].forEach.call(files, function(file, index){
                if ( /\.(jpe?g|png)$/i.test(file.name) ) {
                    images.push(file);
                    var reader = new FileReader();
                    reader.onload = function(event){
                        renderImages.push(event.target.result);
                        previewImages(renderImages);
                    }
                    reader.readAsDataURL(files.item(index));
                }else{
                    console.log('Archivo no permitido');
                }
            });
            previewImages(renderImages);
        }
    });
    $('.gallery').on('click', '.image-cancel',function(){
        let imageIndex = $(this).data('no');
        //console.log(imageIndex);
        images.splice(imageIndex, 1);
        renderImages.splice(imageIndex,1);
        previewImages(renderImages);
    });
    //AJAX
    $('#report').on('submit', function(event){
        // Se evita el propago del submit
        event.preventDefault();
        
         //Se agrega el data del formData
         var formData = new FormData(this);
         formData.delete('images[]');

         images.forEach(function(image){
            formData.append('images[]', image);
         });

        //console.log(formData.get('images[]'));

         $.ajax({
            type:'POST',
            url: '../reports/store',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
                // alert('Validation true! se pudo Añadir los datos<br>'+ {timeOut: 5000});
                alert('Validation true! se pudo Añadir los datos');
                console.log(data);
            },
            error: function(jqXHR, text, error){
                // alert('Validation error! No se pudo Añadir los datos<br>' + error + {timeOut: 5000});
                alert('Validation error! No se pudo Añadir los datos');
                console.log(error);
            }
        });

        // console.log('Enviar formulario');
    });
});