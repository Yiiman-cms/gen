<?php
	/**
	 * @var $menuItems []
	 */
	
	use yii\helpers\Inflector;
	global $mID;
	$name = $generator->moduleName;
echo '<?php'?>
	/**
	 * Site: http://tokapps.ir
	 * AuthorName: gholamreza beheshtian
	 * AuthorNumber:09353466620
	 * AuthorCompany: tokapps
	 *
	 *
	 */
	
	use system\lib\Triggers;
	use yii\base\Event;
	use yii\web\Application;
	
	$dir = basename( __DIR__ );
	
	
	$conf =
		[
			'name'      => $dir ,
			'type'      => [ 'backend' ] ,
			'namespace' => 'system\modules\\' . $dir ,
			'address'   => '' ,
			'menu'      =>
				
				[
					'name' => $dir,
					'title'=>'<?= $name ?>',
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
									echo "'name'=>'$name',";
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
				]
			,
	];
	
	/* < Hooks > */
	{
			if (!defined( 'MTHJK_'.$dir)){
				/* </ Add translates > */
				{
					Event::on(
						Application::className() ,
						Application::EVENT_BEFORE_REQUEST ,
						function () use ( $conf ) {
							Yii::$app->i18n->translations[ $conf['name'] ] = [
								'class'          => 'yii\i18n\PhpMessageSource' ,
								'sourceLanguage' => 'fa-IR' ,
								'basePath'       => '@system/modules/' . $conf['name'] . '/messages' ,
								'fileMap'        => [
									$conf['name'] => 'module.php' ,
								] ,
							];
						}
					);
				}
				/* < Add translates > */
				{
					Event::on(
						Triggers::className() ,
						Triggers::EVENT_AFTER_MENU ,
						function () use ( $conf ) {
							$menu = $conf['menu'];
							if ( empty( $menu['items'] ) ) {
								$title = $conf['menu']['title'];
								$name  = $conf['name'];
								if ( empty( $conf['menu']['icon'] ) ) {
									$icon = 'trip_origin';
								} else {
									$icon = $conf['menu']['icon'];
								}
								echo <<<EOT
				<li class="nav-item">
                        <a class="nav-link " href="/backend/$name" aria-expanded="false"><i class="material-icons">$icon</i><p>$title</p>
                        </a>
                        </li>
EOT;
							
							
							} else {
								$title = $conf['menu']['title'];
								$name  = $conf['name'];
								if ( empty( $conf['menu']['icon'] ) ) {
									$icon = 'trip_origin';
								} else {
									$icon = $conf['menu']['icon'];
								}
								if(!empty($conf['menu']['title'])){
echo <<<EOT

<li class="nav-item">
	<a class="nav-link collapsed" href="#$name" data-toggle="collapse" aria-expanded="false"><i class="material-icons">$icon</i><p>$title<b class="caret"></b></p></a>
	<div class="collapse" id="$name" style="">
		<ul class="nav">
EOT;
			
			foreach ( $menu['items'] as $item ) {
			$sName  = $item['name'];
			$sTitle = $item['title'];
			if ( empty( $item['icon'] ) ) {
			$sIcon = 'trip_origin';
			} else {
			$sIcon = $item['icon'];
			}
			echo <<<EOT
			<li class="nav-item">
				<a class="nav-link" href="/backend/$name/$sName">
					<i class="material-icons">$sIcon</i>
					<p class="sidebar-normal">$sTitle</p>
				</a>
			</li>
			
EOT;
			}
			echo <<<EOT
		
		</ul>
	</div>
</li>
EOT;
								}
								
							
							}
							
							
						}
					);
				}
			}
	}
	/* </ Hooks > */
	
	if (!defined( 'MTHJK_'.$dir)){
		define( 'MTHJK_'.$dir , '1');
	}
	return $conf;
