$(window).scroll(function() {
    $(".col1").addClass('animated fadeInLeft');   
});
$(window).scroll(function() {
    $(".col2").addClass('animated fadeInDown');   
});
$(window).scroll(function() {
    $(".col3").addClass('animated fadeInRight');   
});


//$('.Voyage').mouseover(function() {
//    $(".Voyage").append('<h3>Voyage</h3>');
//    $(".Voyage").filter
//})
//$('.Voyage').mouseout(function() {
//    $(".Voyage").html('');
//})


$('a').hover(
    function(){
        $(this).css('opacity','.7');

        $(this).parent().append('<div class="title">' + a + '</div>');
    },
    function(){
        $(this).css('opacity','1');
        $(this).next().remove('.title');
    }
);

//$('#formcontact').submit(function(e){
//    $('#formcontact')[0].reset();
////    $('#succescontact').append('<p>Votre message a bien été envoyé !');
//}
//                        )




// Nous lions la fonction readurl à tous les inputs contenus dans 
// nos uploaders, pour qu'il se lance à chaque sélection de fichier.
$(".uploader input").change(function(){
  readURL(this, $(this).parents(".uploader"));
});


// Notre fonction readURL
function readURL(input, $uploader) {

// Nous vérifions que la fonction n'a pas été lancée 
  // à tort, sans champ d'input
 if (input.files && input.files[0]) {
   var reader = new FileReader();

   // Nous demandons à notre reader d'afficher l'image 
   // sélectionnée quand elle sera dans le navigateur
   reader.onload = function (e) {
     $uploader.find('.preview').attr('src', e.target.result);
     console.log($uploader.find('.preview'));
     $("#etiquette").hide;
     
     // Nous ajoutons une classe 'full' a l'uploader pour suivre
     // simplement son état
     $uploader.addClass("full");
     
     // Nous nous assurons que le hidden fiend ne pense pas que 
     // l'image a ete supprimée.
     $uploader.find("input[type='hidden']").val('false');
   }   

   // Enfin, nous demandons a notre reader de charger l'image
   reader.readAsDataURL(input.files[0]);
 }
}

$('.uploaders').mouseenter(function() {
    $('#etiquette').css('z-index', '-5');
})

$('.uploaders').mouseleave(function() {
    $('#etiquette').css('z-index', 'inherit');
})

$('.uploaders').change(function() {
    $('#etiquette').css('display', 'none');
})


// Cette partie est là pour supprimer le contenu au clic

// Nous ne la lions qu'aux uploaders pleins
$(document).on('click', '.uploader.full', function(){
  $uploader = $(this);
  
  // Nous supprimons le src de l'image de preview et
  // la classe 'full'
  $uploader.find('.preview').attr('src', null);
  $uploader.find('input[type="file"]').attr('value', null);
  $uploader.removeClass('full');
  // Enfin, nous rendons la valeur du champ cachée égale à 'false' pour
  // avoir une trace après l'envoi de la supression
  $uploader.find("input[type='hidden']").val('true');
})


// div recaptcha responsive
var width = $('.g-recaptcha').parent().width();
if (width < 302) {
	var scale = width / 302;
	$('.g-recaptcha').css('transform', 'scale(' + scale + ')');
	$('.g-recaptcha').css('-webkit-transform', 'scale(' + scale + ')');
	$('.g-recaptcha').css('transform-origin', '0 0');
	$('.g-recaptcha').css('-webkit-transform-origin', '0 0');
}