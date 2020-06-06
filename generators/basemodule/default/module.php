<?php
/**
 * This is the template for generating a module class file.
 */

/* @var $this yii\web\View */
/* @var $generator system\modules\gen\generators\module\Generator */

$className = $generator->moduleID;
$pos = strrpos($className, '\\');
$ns = ltrim('system\modules\\'.$className);
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
use kartik\mpdf\Pdf;
use Yii;
use yii\helpers\ArrayHelper;
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
   
	public $controllerNamespace;
	public $name;
	public $nameSpace;
	public $config = [];
    /**
     * {@inheritdoc}
     */
    public function init()
    {
			// < set Class Parameters >
			{
			$this->config              = include realpath( __DIR__ . '/config.php' );
			$this->nameSpace           = 'system\modules\\' . $this->config['name'];
			$this->controllerNamespace = 'system\modules\\' . $this->config['name'] . '\controllers';
			$this->name                = $this->config['name'];
			
			}
			// </ set Class Parameters >
			
			
			//$this->initI18N();
			$this->initModules();
			//		$this->initMigrations();
			$this->registerTranslations();
			// $this->initComponents();
    }


	/**
	* TranslationTrait manages methods for all translations used in Krajee extensions
	*
	* @property array $i18n
	*
	* @author Kartik Visweswaran <kartikv2@gmail.com>
	* @since  1.8.8
	* Yii i18n messages configuration for generating translations
	* source : https://github.com/kartik-v/yii2-krajee-base/blob/master/TranslationTrait.php
	* Edited by : Yohanes Candrajaya <moo.tensai@gmail.com>
	*
	*
	* @return void
	*/
	public function initI18N() {
	$reflector = new \ReflectionClass( get_class( $this ) );
	$dir       = dirname( $reflector->getFileName() );
	
	if ( ! empty( $this->config['message'] ) ) {
	foreach ( $this->config['message'] as $message ) {
	Yii::setAlias( "@" . $message , $dir );
	$config       = [
	'class'            => 'yii\i18n\PhpMessageSource' ,
	'basePath'         => "@" . $message . "/messages" ,
	'forceTranslation' => true
	];
	$globalConfig = ArrayHelper::getValue( Yii::$app->i18n->translations , $message . "*" , [] );
	if ( ! empty( $globalConfig ) ) {
	$config = array_merge(
	$config ,
	is_array( $globalConfig ) ? $globalConfig : (array) $globalConfig
	);
	}
	Yii::$app->i18n->translations[ $message . "*" ] = $config;
	}
	}
	
	}
	
	protected function registerTranslations() {
	Yii::$app->i18n->translations[ $this->name ] = [
	'class'          => 'yii\i18n\PhpMessageSource' ,
	'sourceLanguage' => Yii::$app->language ,
	'basePath'       => '@system/modules/' . $this->name . '/messages' ,
	'fileMap'        => [
	$this->name => 'module.php' ,
	] ,
	];
	}
	
	public function initComponents() {
	$Option =
	[
	'class' => 'system\modules\setting\components\Options' ,
	];
	
	
	//Yii::$app->components['pdf']= $pdf;
	Yii::$app->setComponents( [ $Option ] );
	}
	
	public function initModules() {
	if ( ! empty( $this->config['modules'] ) ) {
	
	foreach ( $this->config['modules'] as $key => $val ) {
	$this->modules[ $key ] = $val;
	}
	}
	}
	
	public function initMigrations() {
	$classes = getFileList( realpath( __DIR__ . '/migrations' ) );
	if ( ! empty( $classes ) ) {
	foreach ( $classes as $key => $val ) {
	if ( $val['type'] == 'text/x-php' ) {
	$val['name'] = str_replace( '.php' , '' , $val['name'] );
	$cname       = $this->nameSpace . '\migrations\\' . $val['name'];
	$class       = new $cname();
	try {
	$generate = $class->safeUp();
	} catch ( \Exception $e ) {
	}
	
	
	}
	
	}
	}
	
	
	}
	
	/**
	* Translates a message. This is just a wrapper of Yii::t
	*
	* @see Yii::t
	*
	* @param       $category
	* @param       $message
	* @param array $params
	* @param null  $language
	*
	* @return string
	*/
	public static function t( $category , $message , $params = [] , $language = null ) {
	
	return Yii::t( $category , $message , $params , $language );
	}
}
