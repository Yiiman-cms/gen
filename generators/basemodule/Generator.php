<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace system\modules\gen\generators\basemodule;

use Exception;
use function explode;
use function lcfirst;
use function str_replace;
use function strpos;
use system\modules\gen\CodeFile;
use function trim;
use function ucfirst;
use function ucwords;
use function var_dump;
use yii\base\Event;
use yii\helpers\Html;
use Yii;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\web\Application;

/**
 * This generator will generate the skeleton code needed by a module.
 *
 * @property string $controllerNamespace The controllers namespace of the module. This property is read-only.
 * @property bool $modulePath          The directory that contains the module class. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class Generator extends \system\modules\gen\Generator
{
    public $moduleName;
    public $moduleID;
    public $tables;
    public $controllerNames;
    public $hasImage;
//

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ماژول ساز توکاپس';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'ماژول ساز شرکت توکاپس، مخصوص کدنویسان توکاپس';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['moduleID', 'moduleName', 'tables', 'controllerNames'], 'filter', 'filter' => 'trim'],
                [['moduleID', 'moduleName', 'tables'], 'required'],
                [
                    ['moduleID'],
                    'match',
                    'pattern' => '/^[\w\\-]+$/',
                    'message' => 'Only word characters and dashes are allowed.'
                ],

            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'moduleID' => 'شناسه ی ماژول(نام پوشه و URL)',
            'moduleName' => 'نام فارسی ماژول',
            'controllerNames' => 'نام فارسی کنترلر ها',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function hints()
    {
        return [
            'moduleID' => 'This refers to the ID of the module, e.g., <code>admin</code>.',
            'moduleClass' => 'This is the fully qualified class name of the module, e.g., <code>app\modules\admin\Module</code>.',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function successMessage()
    {
        if (Yii::$app->hasModule($this->moduleID)) {
            $link = Html::a(
                'try it now',
                Yii::$app->getUrlManager()->createUrl($this->moduleID),
                ['target' => '_blank']
            );

            return "The module has been generated successfully. You may $link.";
        }

        $output = <<<EOD
<p>The module has been generated successfully.</p>
<p>To access the module, you need to add this to your application configuration:</p>
EOD;
        $code = <<<EOD
<?php
    ......
    'modules' => [
        '{$this->moduleID}' => [
            'class' => '{$this->moduleID}',
        ],
    ],
    ......
EOD;

        return $output . '<pre>' . highlight_string($code, true) . '</pre>';
    }

    /**
     * {@inheritdoc}
     */
    public function requiredTemplates()
    {
        return ['module.php', 'controllers.php', 'view.php'];
    }

    private function GetCamelName($name, $deleteModule = false)
    {
        $name = StringHelper::basename($name);
        $name = str_replace('_', ' ', $name);
        $name = ucwords($name);
//			if ($deleteModule){
        $name = str_replace('Module', '', $name);
//			}


        return str_replace(' ', '', $name);
    }


    private function generateModels($tables)
    {
        foreach ($tables as $key => $table) {
            if (empty($table)) {
                continue;
            }
            $tableClass = $this->GetCamelName(trim($table));
            $modelGenerator = new \system\modules\gen\generators\model\Generator();
            $modelGenerator->tableName = $table;
            $modelGenerator->modelClass = $tableClass;
            $modelGenerator->ns = 'system\modules\\' . $this->moduleID . '\models';
            $modelGenerator->useTablePrefix = true;
            $modelGenerator->generateRelationsFromCurrentSchema = true;
            $modelGenerator->generateLabelsFromComments = true;
            $modelGenerator->enableI18N = true;
            $modelGenerator->messageCategory = $this->moduleID;
            $modelGenerator->useSchemaName = true;
            $modelGenerator->queryNs = $modelGenerator->ns;
            $modelGenerator->saveStickyAttributes();


            $files = $modelGenerator->generate();
            foreach ($files as $file) {
                $file->save();
            }
        }

    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {

        /* < module persian name > */
        {
            $GLOBALS['mName'] = $this->moduleName;
            $GLOBALS['mID'] = $this->moduleID;
            $GLOBALS['hasImage'] = $this->hasImage;
        }
        /* </ module persian name > */

        $files = [];
        $modulePath = $this->getModulePath();
        $menuItems = [];
        /* < Generate Models > */
        {

            $tables = explode('|', $this->tables);
            $this->generateModels($tables);

            foreach ($tables as $key => $table) {
                if (empty($table)) {
                    continue;
                }
                $controllerName = explode('|', $this->controllerNames);
                $tableClass = $this->GetCamelName($table);
                $modelGenerator = new \system\modules\gen\generators\model\Generator();
                $modelGenerator->tableName = $table;
                $modelGenerator->modelClass = ucfirst($tableClass);
                $modelGenerator->ns = 'system\modules\\' . $this->moduleID . '\models';
                $modelGenerator->useTablePrefix = true;
                $modelGenerator->generateRelationsFromCurrentSchema = true;
                $modelGenerator->generateLabelsFromComments = true;
                $modelGenerator->enableI18N = true;
                $modelGenerator->messageCategory = $this->moduleID;
                $modelGenerator->useSchemaName = true;
                $modelGenerator->queryNs = $modelGenerator->ns;
                $modelGenerator->saveStickyAttributes();


                $files = $modelGenerator->generate();

                foreach ($files as $file) {

                    if (!empty($modelGenerator->relations[$table])) {

                        $rels = $modelGenerator->relations[$table];

                        foreach ($rels as $keyRel => $rel) {


                            /* < Get Relation Caption > */
                            {
                                $ModelRelation = new \system\modules\gen\generators\model\Generator();
                                $ModelRelation->tableName = $rel['table'];
                                $ModelRelation->modelClass = ucfirst($tableClass);
                                $ModelRelation->ns = 'system\modules\\' . $this->moduleID . '\models';
                                $ModelRelation->useTablePrefix = true;
                                $ModelRelation->generateRelationsFromCurrentSchema = true;
                                $ModelRelation->generateLabelsFromComments = true;
                                $ModelRelation->enableI18N = true;
                                $ModelRelation->messageCategory = $this->moduleID;
                                $ModelRelation->useSchemaName = true;
                                $ModelRelation->queryNs = $ModelRelation->ns;
                            }
                            /* </ Get Relation Caption > */
                            $relationTableName = $rels[$keyRel]['table'];
                            try {
                                $rels[$keyRel]['name'] = $ModelRelation->generateLabels(($ModelRelation->getDbConnection())->getTableSchema($rels[$keyRel]['table']))['title'];
                            } catch (Exception $e) {
                                $rels[$keyRel]['name'] = '';
                            }

                            $relationModuleName = strpos($relationTableName, 'module');

                            if ($relationModuleName === 0) {
                                $relationTableName = explode('_', $relationTableName);
                                $rels[$keyRel]['module'] = $relationTableName[1];


                            } else {
                                $rels[$keyRel]['module'] = $this->moduleID;
                            }
                        }
                    } else {
                        $rels = '';
                    }


                    $GLOBALS['relations'] = $rels;
                    $file->save();
                    /* < CURD GENERATE > */
                    {
                        $crud = new \system\modules\gen\generators\crud\Generator();
                        $crud->CrudName = $_POST[$table];
                        $crud->modelClass = 'system\modules\\' . $this->moduleID . '\models\\' . ucfirst(
                                $tableClass
                            );

                        $crud->searchModelClass = 'system\modules\\' . $this->moduleID . '\models\\Search' . ucfirst(
                                $tableClass
                            );
                        if ($key == 0) {
                            $crud->controllerClass = 'system\modules\\' . $this->moduleID . '\controllers\DefaultController';
                        } else {
                            $crud->controllerClass = 'system\modules\\' . $this->moduleID . '\controllers\\' . ucfirst(
                                    $this->GetCamelName($table, true)
                                ) . 'Controller';
                        }


                        if ($key == 0) {

                            $crud->viewPath = '@system\modules\\' . $this->moduleID . '\views\default';
                        } else {

                            $viewName = lcfirst($this->GetCamelName($crud->controllerClass));
                            $viewName = str_replace('Controller', '', $viewName);
                            $crud->viewPath = '@system\modules\\' . $this->moduleID . '\views\\' . $viewName;
                        }
                        $crud->baseControllerClass = 'yii\web\Controller';
                        $crud->enableI18N = true;
                        $crud->enablePjax = true;
                        $crud->messageCategory = $this->moduleID;


                        $moduleID = $this->moduleID;


                        Yii::$app->i18n->translations[$moduleID] = [
                            'class' => 'yii\i18n\PhpMessageSource',
                            'sourceLanguage' => 'fa-IR',
                            'basePath' => '@common/config/translates',
                            'fileMap' => [
                                $moduleID => 'module.php',
                            ],
                        ];
                        /* < Add Menu > */
                        {
                            if ($key == 0) {
                                $menuItems[] =
                                    [
                                        'title' => $_POST[$table],
                                        'id' => ''
                                    ];
                            } else {

                                $menuItems[] =
                                    [
                                        'title' => $_POST[$table],
                                        'id' => $tableClass
                                    ];
                            }
                        }
                        /* </ Add Menu > */
                        $crud->saveStickyAttributes();
                        $crudFiles = $crud->generate();
                        foreach ($crudFiles as $crudFile) {
                            $crudFile->save();
                        }
                    }
                    /* </ CURD GENERATE > */
                }


            }


        }
        /* </ Generate Models > */

        $files[] = new CodeFile(
            $modulePath . '/' . StringHelper::basename('Module') . '.php',
            $this->render("module.php")
        );
        $files[] = new CodeFile(
            $modulePath . '/' . StringHelper::basename('config') . '.php',
            $this->render("config.php", ['menuItems' => $menuItems])
        );
//			$files[] = new CodeFile(
//				$modulePath . '/controllers/DefaultController.php' ,
//				$this->render( "controllers.php" )
//			);
//			$files[] = new CodeFile(
//				$modulePath . '/views/default/index.php' ,
//				$this->render( "view.php" )
//			);

        return $files;
    }


//		public function validate($attributeNames = NULL, $clearErrors = true){
//			echo '<pre style="direction:ltr">';
//			var_dump($this);
//			die();
//
//		}

    /**
     * Validates [[moduleClass]] to make sure it is a fully qualified class name.
     */
    public function validateModuleClass()
    {


        if (strpos($this->moduleClass, '\\') === false || Yii::getAlias(
                '@' . str_replace('\\', '/', $this->moduleClass),
                false
            ) === false) {
            $this->addError('moduleClass', 'Module class must be properly namespaced.');
        }
        if (empty($this->moduleClass) || substr_compare($this->moduleClass, '\\', -1, 1) === 0) {
            $this->addError(
                'moduleClass',
                'Module class name must not be empty. Please enter a fully qualified class name. e.g. "app\\modules\\admin\\Module".'
            );
        }
    }

    /**
     * @return bool the directory that contains the module class
     */
    public function getModulePath()
    {
        return Yii::getAlias(
            '@system/modules/' . $this->moduleID
        );
    }

    /**
     * @return string the controllers namespace of the module.
     */
    public function getControllerNamespace()
    {
        return 'system\modules\\' . $this->moduleID . '\controllers';
    }

}
