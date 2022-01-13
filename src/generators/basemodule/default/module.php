<?php
/**
 * This is the template for generating a module class file.
 */

/* @var $this yii\web\View */
/* @var $generator YiiMan\gen\generators\module\Generator */

$className = $generator->moduleID;
$pos = strrpos($className, '\\');
$ns = ltrim('YiiMan\YiiBasics\modules\\'.$className);
$className = substr($className, $pos + 1);

echo "<?php\n";
?>
/**
* Site: http://tokapps.ir
* AuthorName: gholamreza beheshtian
* AuthorNumber:09353466620
* AuthorCompany: tokapps
*
*
*/
namespace <?= $ns ?>;

/**
 * <?= $generator->moduleID ?> module definition class
 */

use Yii;
use yii\helpers\ArrayHelper;
class Module extends \YiiMan\YiiBasics\lib\Module
{
    /**
     * {@inheritdoc}
     */
   
	public $controllerNamespace='<?= $ns.'/controllers' ?>';

    public static function menus()
    {
        return
            [
            'title'=>'<?=  $generator->moduleName ?>',
            <?php
            if (!empty( $menuItems) && count($menuItems)>1){
                ?>
                'items'=>
                [
                <?php
                foreach($menuItems as $item){
                    $name=$item['id'];
                    if (!empty( $name)){
                        $pieces = preg_split('/(?=[A-Z])/',$name);
                        $name='';
                        foreach ($pieces as $wKey=>$word){
                            if (empty( $word)){
                                continue;
                            }else{



                                if (!empty( $pieces[($wKey-1)]) ){
                                    $name.='-';
                                }
                                $name.=lcfirst($word);

                            }
                        }

                    }



                    if (!empty( $item['title'])){
                        $title=$item['title'];
                        echo '[';
                        echo "'url'=>'$name',";
                        echo "'title'=>'$title',";

                        echo '],';
                    }

                    //							    $title=str_replace( ' ها','',$title);
                    //								echo '[';
                    //								echo "'name'=>'".$name."create',";
                    //								echo "'title'=>'افزودن $title',";
                    //								echo '],';
                }
                ?>
                ]
                <?php
            }else{
                echo '\'url\'=> $dir.\'/index\'';
            }


            ?>
            ];
    }



}
