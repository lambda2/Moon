
var statSite;

$(document).ready(function() {
    loadTweets();
    
    var item = $('.dewing');
    item.css('opacity','0.1');
    
    createChart();
});  

$(window).scroll(function () { 
    showSequence(mainSlide.getPos());
});

$(window).resize(function() {
    createChart();
});

$('#sendMail').click(function(){
    if(verifierChampsMail()){
        $('#formulaireEmail').slideUp(400);
        $('.loader-ajax').showOpa();
        $.ajax({
            url: 'index.php?m=l&ajax=sendMail&action=send',
            data: { 
                exp: $('#email').attr("value"), 
                msg: $('#message').attr("value"), 
                typemsg: $('.btn-group > .btn.button.active').html()
            },
            success: function(dataa) {
                $('.loader-ajax').hide(function(){
                    if(dataa == "0"){
                        $('#alertMail').html(trads["err_email"]);
                        $('#alertMail').show().showOpa();
                        $('#formulaireEmail').slideDown(400);
                    }
                    else {
                        $('#succesMail').show().showOpa();
                    }
                
                });
            }
        });
    }
});



function verifierChampsMail(){
    var erreur = "";
    var valide = true;
    if($('#message').attr("value") == ""){
        valide = false;
        erreur = trads["un_petit_mot"]+"\n";
        $('#message').focus();
    }else if($('.btn-group > .btn.button.active').html() == "" ){
        valide = false;
        erreur = trads["un_theme"]+"\n";
    }else if($('#email').attr("value") == ""){
        valide = false;
        erreur = trads["un_email"]+"\n";
        $('#email').focus();
    }
    
    if(valide == false){
        $('#alertMail').html(erreur);
        $('#alertMail').show().showOpa();
    }
    
    return valide;
}

$('#connectMainButton').click(function(){
    $('#succes-login').hideOpa();
    $('#error-login').hideOpa();
    if(verifierChampsLogin()){
        $('#loginspbar').stop().css("width",'0px');
        $('#loginspbar').stop().removeClass('bar-success').removeClass('bar-danger').css("width",'0px');
        $('#loginspbar').animate({
            width: '100%'
        }, 3000);
        $.ajax({
            url: 'index.php?m=l&ajax=connectUser&action=c',
            data: { 
                u: $('#inputEmail').attr("value"), 
                p: $('#inputPassword').attr("value")
            },
            success: function(dataa) {
                console.log("u: " + $('#inputEmail').attr("value") + ", p: " + $('#inputPassword').attr("value"))
                console.log("data: " + dataa)
                dataa = dataa.replace("\n", "");
                console.log("data: " + dataa)
                if(dataa == "0"){
                    console.log("echec de la connexion");
                    $('#loginspbar').addClass('bar-danger')
                    $('#loginspbar').stop().css("width",'0px');
                    $('#error-login').html("La connexion a échouée.").showOpa();
                    
                }
                else if (dataa == "1"){
                    console.log("reussite de la connexion");
                    $('#loginspbar').addClass('bar-success');
                    $('#login-aera').slideUp();
                    $('#loading-login-aera').slideUp();
                    $('#succes-login').html("Connexion réussie.").showOpa();
                    document.location.href="index.php?stat=4";
                }
                else {
                    console.log("statut inconnu (echec) de la connexion");
                    $('#succesLogin').html(dataa);
                    $('#loginspbar').stop().css("width",'0px');
                }
                $('#loginspbar').stop()
            }
        });
    }
    else {
        $('#error-login').html("Un ou plusieurs champs ne sont pas correctement remplis.").showOpa();
    }
});

function verifierChampsLogin(){
    return ($('#inputEmail').val() != "" && $('#inputPassword').val() != "")
}

$('#deconnexion').click(function(){
    $.ajax({
        url: 'index.php?m=l&ajax=connectUser&action=d',
        success: function(dataa) {
            dataa = dataa.replace("\n", "");
            if(dataa == "0"){
                console.log("echec de la deconnexion");
                    
            }
            else if (dataa == "1"){
                console.log("reussite de la deconnexion");
                document.location.href="index.php?stat=3";
            }
            else {
                console.log("statut inconnu (echec) de la deconnexion");
            }
        }
    });
});



function updateMenu(){
    var pages = getPages();
    $('.nav > li[data-move="dynamic"]').remove();
    
    $.each(pages,function(index,value){
        $('.nav > li[data-move="static"]').first().before('<li data-move="dynamic" data-refer="'+index+'"><a onclick="mainSlide.slide('+index+',400)">'+value+'</a></li>');
    });
    
}

function loadTweets(){
    $('#tweet-aera').html("");
    $('#tweet-aera').tweets({
        tweets:4,
        username: "lambdaweb"
    });
/*if($('#tweet-aera').html() == ""){
        $('#tweet-aera').html("Aucune actualité récente.");
    }*/
}

function createChart(){
    statSite = new Highcharts.Chart({
        chart: {
            renderTo: 'statConcurrence',
            type: 'column'
        },
        title: {
            style: {
                color: '#777'
            },
            
            text: trads['compa_concur']
        },
        subtitle: {
            style: {
                color: '#AAA'
            },
            text: trads['we_are_blue']
        },
        xAxis: {
            title: {
                style: {
                    color: '#AAA'
                }
            },
            categories: [trads['site_vitrine'],trads['blog_cms'],trads['e_commerce']]
        },
        yAxis: {
            
            title: {
                style: {
                    color: '#AAA'
                },
                text: trads['prix_moyen_e']
            }
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                shadow: false,
                showCheckBox: true,
                borderRadius: 3         
            }
        },
        tooltip: {
            formatter: function() {
                return trads['chez'] + ' ' + this.series.name +'<br/>'+
                trads['un'] + ' ' + this.x.toString().toLowerCase() + trads['coute_au_moins'] + this.y + ' €';
            }
        },
        
        series: [{
            name: 'Lambdaweb',
            color: '#79B2E2',
            data: [400, 600, 1200]
        }, {
            name: 'Icare',
            color: '#ddd',
            data: [1600, 2200, 3350]
        }, {
            name: 'ComAndWeb',
            color: '#999',
            data: [900, 1900, 3200]
        }, {
            name: 'Solanciel',
            color: '#bbb',
            data: [1300, 1500, 2950]
        }, {
            name: 'Reventys',
            color: '#888',
            data: [1000, 1600, 3450]
        }]
    });
}
    
function isInWindow(object){

    if(object){
        var deb = $(window).scrollTop();
        var fin = $(window).height();
        if(object.offset().top < fin+deb){
            return true;
        }
        else {
            return false;
        }
    }
    else{
        return false;
    }
}

function getHiddenDewing(){
    return $('.dewing').filter(function(index) {
        return $(this).css('opacity') < 1;
    }).first();
}

function showSequence(page) {
    var item = $('.dewing').filter(function() {
        return ($(this).css('opacity') < 1 && $(this).data('page') == page);
    }).first();
  
    if(isInWindow(item) && mainSlide.getPos() == page) {
        item.animate({
            opacity: 1
        },200);
        window.setTimeout("showSequence("+page+")", 10);
        return true;
    }
    else {
        return false;
    }
}



function getPourcentBarre(){
    return ((mainSlide.getPos()*4)/100)+1;
}

function bougerProgressBarManuel(step){
    $('#pBar').animate({
        width: step*(100/4)+'%'
    }, 500);
}

function hideOtherSlides(index, nbSlides){
/*for(var i=0; i < nbSlides; i++){
        var currentHead = "#logo-banner-"+i;
        $(currentHead).hide();
    }
    currentHead = "#logo-banner-"+index;
    $(currentHead).fadeIn();*/
}

function setTouchBackTop(index){
    mainSlide.slide(4-index, 300);
}

function setTouchBackMain(index){
    headerSlide.slide(4-index, 300);
    if(headerSlide.getPos() == 4 && $('#topSliderUl').height() != 400){
        $('#topSliderUl').animate({
            height:'400px'
        },100);
    }
    else if(headerSlide.getPos() != 4){
        $('#topSliderUl').animate({
            height:'200px'
        },100);
    }
}

function rotateBanner(index){
    var nbSlides = 5;
    hideOtherSlides(index,nbSlides);
}

function getPages(){
    var pages = [
    trads['title_accueil'],
    trads['title_sites'],
    trads['title_app'],
    trads['title_conseil'],
    trads['title_contact']
    ];
    
    return pages;
}

function refreshMobile(){
    var nbSlides = 5;
    
    var pages = getPages();
    
    if(mainSlide.getPos()==0)
        $('#boutonPrevMobile').fadeOut('fast').data("page","");
    else {
        $('#boutonPrevMobile').fadeIn('fast').data("page",pages[mainSlide.getPos()-1]);
    }
   
    if(mainSlide.getPos()==(nbSlides-1))
        $('#boutonNextMobile').fadeOut('fast').data("page","");
    else
        $('#boutonNextMobile').fadeIn('fast').data("page",pages[mainSlide.getPos()+1]);
    
    $('#boutonNextMobile').html($('#boutonNextMobile').data("page")+'<i class="icon-arrow-right">'+'</i>');
    $('#boutonPrevMobile').html('<i class="icon-arrow-left">'+'</i>'+$('#boutonPrevMobile').data("page"));
    
    
   
   
}

function setActiveRef(){
    
    var newRef = 
    $('.progressContainer > .horinav[data-refer='
        +mainSlide.getPos()
        +']');
    
    var newRefMenu = 
    $('.nav > li[data-refer='
        +mainSlide.getPos()
        +']');
    
    setTouchBackMain(mainSlide.getPos());
    $('#pBar').animate({
        width: newRef.data('pos')
    }, 500);
    refreshMobile();
    rotateBanner(mainSlide.getPos());
    $('.progressContainer > .link-active').removeClass('link-active');
    
    $('.nav > li[data-move="dynamic"]').removeClass('active');
    
    newRef.addClass('link-active');
    newRefMenu.addClass('active');
    showSequence(mainSlide.getPos());
    $('.ajax-anchor').hideOpa();
}

function genererMiniaturesRealisations(){
    var i = $('.asc-container > p');
    $.each(i,function(index,value){
        $(this).css('background-image','url(Assets/images/realisations/'+ $(this).data('app') +'/miniature.png)');
    });
    
}

function bougerBackgrounds(){
    var j = $('.site-preview');
    $.each(j,function(index,value){
        $(this).generateRandomBackgroundPosition();
    });
}

function initialiserRealisations(){
    var i = $('.asc-content');
    $.each(i,function(index,value){
        $(this).hide();
    });
    
    $('.asc-container > p').click(function(){
        genererMiniaturesRealisations();
        $(this).css('background-image','url(Assets/images/realisations/'+ $(this).data('app') +'/logo.png)');
        var data = $(this).data('app');
        $('.ajax-anchor').hideOpa();
        setTimeout(afficherReal(data),500);
        bougerBackgrounds();
        
    })
}


function fixLoaders(){
    $('.ajax-anchor').hideOpa();
    $('.loader-ajax').hideOpa();
    $('#succes-login').hideOpa();
    $('#error-login').hideOpa();
    $('#alertMail').hide();
    $('#succesMail').hide();
}

function fixTooltips(){
    
    // Navigation du menu
    $('#link-accueil').tooltip({
        html:"true",
        title:trads["link-accueil"]
    });
    $('#link-websites').tooltip({
        html:"true",
        title:trads["link-websites"]
    });
    $('#link-applications').tooltip({
        html:"true",
        title:trads["link-applications"]
    });
    $('#link-advice').tooltip({
        html:"true",
        title:trads["link-advice"]
    });
    $('#link-contact-us').tooltip({
        html:"true",
        title:trads["link-contact-us"]
    });
        
   
}


function getDistance(orig,dest){

var service = new google.maps.DistanceMatrixService();
service.getDistanceMatrix(
  {
    origins: [orig],
    destinations: [dest],
    travelMode: google.maps.TravelMode.DRIVING,
    avoidHighways: true,
    avoidTolls: false
  }, function(response, status) {
  if (status == google.maps.DistanceMatrixStatus.OK) {
    var r = new Array();
    var origins = response.originAddresses;
    var destinations = response.destinationAddresses;
    console.log();
    for (var i = 0; i < origins.length; i++) {
      var results = response.rows[i].elements;
      for (var j = 0; j < results.length; j++) {
        var element = results[j];
        r[0] = element.distance.text;
        r[0] = r[0].replace(" ", "");
        r[1] = element.duration.text;
        $('#inputDistance').val(r[0].replace("km", ""));
        //var from = origins[i];
        //var to = destinations[j];
      }
    }
  }
  return r;
});
return service;
}

function afficherReal(data){
    $('.loader-ajax').showOpa();
    $.ajax({
        url: 'index.php?m=l&ajax=realisations&action=detailReal&idReal='+ data,
        success: function(dataa) {
            $('.ajax-anchor').html(dataa);
            $('.loader-ajax').hide(function(){
                // Icones de plateformes
                var i = $('.icon-plt');
                $.each(i,function(index,value){
                    $(this).popover({
                        content:trads["dispo-plateforme"] + $(this).data('platform'),
                        trigger:"hover",
                        placement:"top"
                    });
                });
                $('.ajax-anchor').showOpa();
                
            });
        }
    });
}

$('.horinav').click(function(){
    mainSlide.slide($(this).data('refer'), 200);
});

$('.slinker').click(function(){
    mainSlide.slide($(this).data('refer'), 200);
});

$(window).load(function() { 
    // On fait disparaitre notre page de chargement
    genererMiniaturesRealisations();
    initialiserRealisations();
    $('#topSliderUl').animate({
        height:'400px'
    },100,function(){
        $("#loader-screen").fadeOut('fast');
    });
    setTimeout(function(){
        $('#status-aera').hideOpa();
        $('#error-aera').hideOpa();
    },5000);
    fixLoaders();
    fixTooltips();
    updateMenu();
}



);

// Fonction de mise a jour

$('#modifierInfoProfil').click(function(){
    $('.user-infos, .user-edit').toggle();
});


$('input[data-type="userInfo"], select[data-type="userInfo"]').change(function(event){
    console.log("touche appuyé");
    var but = $('button[data-butrefer="'+$(this).attr('id')+'"]');
    but.html('<i class="icon-time upPending"></i>');
    var to = setTimeout(
        updateUserField(but.data("field"), 
            $(this).attr("value"),
            but,
            $(this).data("target")
            ),2000);      
});

$('#inputDestination').change(function(event){
    if($('#inputOrigine').val() != ""){
        getDistance($('#inputOrigine').val(), $('#inputDestination').val());
    }
});

$('*[data-toggle-target]').click(function(){
    var i= $(this).data("after");
    $(this).data("after",$(this).html());
    $(this).html(i);
    $('#'+$(this).data("toggle-target")).toggle();
    $('#'+$(this).data("toggle-target")+'-i').toggle();
});

$('button[data-role="insert"]').click(function(){
    var but = $(this);
    var elems = $('#'+$(this).data("form")+' > *[data-type]');
    var tab = new Array();
    elems.each(function(index){
        if($(this).is('select')){
            tab[$(this).data("catr")] = $(this).children("option:selected").attr("value");
        }
        else {
            tab[$(this).data("catr")] = $(this).val();
        }
    });
    var s=""; var fi=true; 
    for(var key in tab){ 
        if(fi){s= s + key+':'+tab[key]; fi=false; }
        else{s= s + ','+key+':'+tab[key];}
    }
    console.log(s);
    but.html('<i class="icon-time upPending"></i>');
    var to = setTimeout(
        insertField(
            s, 
            $(this),
            $(this).data("target")
            ),2000);      
});

function updateUserField(ufield, uvalue,but,target){
    console.log("maj déclenchée");
    $.ajax({
        url: 'index.php?m=l&ajax=update&action=update',
        data: { 
            field: ufield, 
            newv: uvalue, 
            target: target,
            id: userid
        },
        success: function(dataa) {
            console.log("maj finie.\nResultat :");
            
                console.log(dataa);
                dataa = dataa.replace("\n", "");
                if(dataa == "0"){
                    console.log("echec de la mise a jour");
                    but.html('<i class="icon-warning-sign upError"></i>');
                }
                else if (dataa == "1"){
                    console.log("reussite de la mise a jour");
                    but.html('<i class="icon-ok-sign upSuccess"></i>');
                    $('#'+but.data("form")).slideUp();
                }
                else {
                    console.log("statut inconnu (echec) de la mise a jour");
                }
            
        }
    });
    
}

function insertField(fields,but,target){
    console.log("insert déclenchée");
    $.ajax({
        url: 'index.php?m=l&ajax=update&action=insert',
        data: { 
            fields: fields,
            target: target,
            id: userid
        },
        success: function(dataa) {
            console.log("maj finie.\nResultat :");
            
                console.log(dataa);
                dataa = dataa.replace("\n", "");
                if(dataa == "0"){
                    console.log("echec de la mise a jour");
                    but.html('<i class="icon-warning-sign upError"></i>');
                }
                else if (dataa == "1"){
                    console.log("reussite de la mise a jour");
                    but.html('<i class="icon-ok-sign upSuccess"></i>');
                }
                else {
                    console.log("statut inconnu (echec) de la mise a jour");
                }
            
        }
    });
    
}