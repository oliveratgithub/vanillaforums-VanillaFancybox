<?php if (!defined('APPLICATION')) exit();

/**
 * Define the plugin:
 */
$PluginInfo['VanillaFancybox'] = array(
	'Name'			=> 'jQuery Fancybox',
	'Description'	=> 'Adds the jQuery Fancybox plugin to display images in a nice way.',
	'Version'		=> '1.2',
	'Author'		=> 'Oliver Raduner',
	'AuthorEmail'	=> 'vanilla@raduner.ch',
	'AuthorUrl'		=> 'http://raduner.ch/',
	'License'		=> 'Free',
	'RequiredApplications' => array('Vanilla' => '>=2.0.18'),
	'RequiredTheme'	=> FALSE,
	'RequiredPlugins' => FALSE,
	'HasLocale'		=> FALSE,
	'RegisterPermissions' => FALSE,
	'SettingsUrl'	=> FALSE,
	'SettingsPermission' => FALSE,
	'MobileFriendly' => FALSE
);


/**
 * Vanilla Fancybox-Plugin
 *
 * Creates an on-site "popup" for diaplying images, using the jQuery Fancybox library
 *
 * @version 1.2
 * @date 04-OCT-2011
 * @author Oliver Raduner <vanilla@raduner.ch>
 * 
 * @link http://fancybox.net/ jQuery Fancybox Plugin
 * 
 * @todo Use jQuery to automatically add fancybox-tag to images
 */
class VanillaFancyboxPlugin extends Gdn_Plugin
{	
	
	/**
	 * Hack the Base Render in order to achieve our goal
	 * 
	 * @version 1.2
	 * @since 1.0
	 */
	public function Base_Render_Before($Sender)
	{
		// Show the Plugin only on specific pages...
		$DisplayOn =  array('activitycontroller', 'discussioncontroller', 'profilecontroller', 'messagecontroller');
		if (!InArrayI($Sender->ControllerName, $DisplayOn)) return;
		
		// Attach the Plugin's JavaScript & CSS to the site
		$Sender->AddJsFile($this->GetResource('js/jquery.fancybox.pack.js', FALSE, FALSE));
		$Sender->AddJsFile($this->GetResource('js/jquery.easing.pack.js', FALSE, FALSE));
		$Sender->AddCSSFile($this->GetResource('style/jquery.fancybox.css', FALSE, FALSE));
		
		// The jQuery configs for the Fancybox plugin
		/* SANDBOX
					$.get("test.php", function(data){
						alert("Data Loaded: " + data);
					});
		---
				if ($(this).parent().is("a")){
					var src = $(this).parent("a").attr("href");
				}
		---		
					if (IsValidImageUrl($(this).parent().attr("href"), ImageCheckCallback) == true){
						$(this).wrap("<a class=\"fancybox\" href=\"" + $(this).parent("a").attr("href") + "\" />");
					} else {
						return false;
					}
		---
		function ImageCheckCallback(url, answer){
				return answer;
			}
			function IsValidImageUrl(url, callback){
			    $("<img>", {
			        src:	url,
			        error:	function() { callback(url, false); },
			        load:	function() { callback(url, true); }
			    });
			}
		---
		$("<img />")
			.bind("error", function(event) { alert("error 1"); })
			.bind("load", function(event) { alert("load 1"); })
			.attr("src", "http://l.yimg.com/cv/mntl/tra/11q2/img_48880236.jpg");
		---
		http://stackoverflow.com/questions/4669111/is-this-a-valid-test-to-check-if-a-url-refers-to-an-image-in-js-jquery
		http://stackoverflow.com/questions/6681741/check-for-file-existence-on-the-web-using-url-passed
		*/
		$FancyboxJQuerySource = '
		<script type="text/javascript">
		jQuery(document).ready(function(){
			function checkImageUrl(url)
			{
				return $("<img />")
					.bind("error", function(event){ return false; })
					.bind("load",  function(event){ return true; })
					.attr("src", url);
			}
			
			$("img, a > img").not("a.Title img, a.Photo img, a.ProfileLink img, .emoticon").each(function(){
				if ($(this).parent().is("a")){
					if (checkImageUrl($(this).parent().attr("href")) == true){
						$(this).wrap("<a class=\"fancybox\" href=\"" + $(this).parent("a").attr("href") + "\" />");
					} else {
						return false;
					}
				} else {
					$(this).wrap("<a class=\"fancybox\" href=\"" + $(this).attr("src") + "\" />");
				}
			});
			
			$("a.fancybox").fancybox({
				"autoScale"		:	false,
				"transitionIn"	:	"elastic",
				"transitionOut"	:	"elastic",
				"speedIn"		:	600, 
				"speedOut"		:	200,
				"padding"		:	0,
				"modal"			:	false, 
				"overlayShow"	:	true,
				"overlayOpacity":	0.4,
				"overlayColor"	:	"#666",
				"opacity"		:	false,
				"titleShow"		:	true,
				"titlePosition"	:	"over",
				"hideOnOverlayClick" : true,
				"hideOnContentClick" : true,
				"enableEscapeButton" : true,
				"cyclic"		:	true,
				"changeFade"	:	0,
				"autoDimensions" : false
			});
		});
		</script>
		';
		
		// Add the jQuery JavaScript to the page
		$Sender->Head->AddString($FancyboxJQuerySource);
		
	}

	/**
	 * Initialize required data
	 *
	 * @since 1.0
	 * @version 1.0
	 */
	public function Setup() { }	
		
}

?>