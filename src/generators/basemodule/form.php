<?php
/* @var $this yii\web\View */

use YiiMan\YiiBasics\lib\View;

/* @var $form yii\widgets\ActiveForm */
/* @var $generator YiiMan\gen\generators\module\Generator */
$posted = json_encode($_POST);
$js = <<<JS
$('#generator-moduleid').change(function(e) {
  var value=$(this).val();
  
  var data={};
  
  $.ajax({
          type: 'post',
          data: {action:'get_module_tables',moduleID:value},
          beforeSend: function (data) {
          
          }
      }).done(function (data) {
          
          	$('#generator-tables').val(data);
          	$('.field-generator-modulename').show();
          	$('.custom-divs').remove();
          	$('.module-form').append(
          	    '<div class="custom-divs"></div>'
          	);
          	$('.custom-divs').append('<p>جداول مربوط به این ماژول یافت شد:</p>');
          	$('.custom-divs').append('<p style="color:green">'+data+'</p>');
          	$('.custom-divs').append('<h3>لطفا نام های فارسی و توضیحات هر جدول را وارد نمایید:</h3>');
          	var tables=data.split('|');
          	
          	$.each(tables,function(i,d){
          	    if (d.length>1){
          	   $('.custom-divs').append("" +
          	 "<div class='form-group field-generator-moduleid required'>"+
					"<label class='control-label help' for='generator-moduleid' data-original-title='''' title='''>نام فارسی جدول "+d+" </label>"+
					"<input type='text' id='"+d+"' class='form-control' name='"+d+"' aria-required='true' aria-invalid='false'>"+
					"<div class='hint-block'>This refers to the ID of the module, e.g., <code>admin</code>.</div>"+
					"<div class='help-block'></div>"+
			"</div>");
          	    }
          	});
      });
});


$(document).ready(function() {
  $('.field-generator-template').hide();
  $('.field-generator-modulename').hide();
  $('.field-generator-controllernames').hide();
  
  var tableNames=$('#generator-tables').val();
  if (tableNames.length>1){
      var POST = $posted;

  			$('.field-generator-modulename').show();
          	$('.custom-divs').remove();
          	$('.module-form').append(
          	    '<div class="custom-divs"></div>'
          	);
          	$('.custom-divs').append('<p>جداول مربوط به این ماژول یافت شد:</p>');
          	$('.custom-divs').append('<p style="color:green">'+tableNames+'</p>');
          	$('.custom-divs').append('<h3>لطفا نام های فارسی و توضیحات هر جدول را وارد نمایید:</h3>');
          	var tables=tableNames.split('|');
          	
          	$.each(tables,function(i,d){
          	    if (d.length>1){
          	   $('.custom-divs').append("" +
          	 "<div class='form-group field-generator-moduleid required'>"+
					"<label class='control-label help' for='generator-moduleid' data-original-title='''' title='''>نام فارسی جدول "+d+" </label>"+
					"<input type='text' id='"+d+"' class='form-control' name='"+d+"' value='"+POST[d]+"' aria-required='true' aria-invalid='false'>"+
					"<div class='hint-block'>This refers to the ID of the module, e.g., <code>admin</code>.</div>"+
					"<div class='help-block'></div>"+
			"</div>");
          	    }
          	});
  }
});
JS;
$this->registerJs($js, View::POS_END);
?>
<div class="module-form">
    <style>
        form, body, h1, h2, p {
            direction: rtl
        }

        .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
            float: right !important;
        }

        .list-group .glyphicon {
            float: left;
            transform: rotate(-180deg);
        }
    </style>
    <?php

    echo $form->field($generator, 'hasImage')->dropDownList([0 => 'خیر', 1 => 'بله'])->label('تصویر داشته باشد؟');
    echo $form->field($generator, 'moduleID');
    echo $form->field($generator, 'moduleName');
    //		echo $form->field( $generator , 'controllerNames' );
    echo $form->field($generator, 'tables')->hiddenInput()->label(false);
    ?>
</div>
