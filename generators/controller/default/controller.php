<?php
/**
 * This is the template for generating a controllers class file.
 */

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator system\modules\gen\generators\controller\Generator */

echo "<?php\n";
?>

namespace <?= $generator->getControllerNamespace() ?>;

class <?= StringHelper::basename($generator->controllerClass) ?> extends system\lib\Controller<?php /// '\\' . trim($generator->baseClass, '\\') . "\n" ?>
{
<?php foreach ($generator->getActionIDs() as $action): ?>
    public function action<?= Inflector::id2camel($action) ?>()
    {
        return $this->render('<?= $action ?>');
    }

	protected function upload(){
	
	
	
	}
<?php endforeach; ?>
}
