<?php // no direct access

defined('_JEXEC') or die('Restricted access'); 


$pinboard = $params->get('pinboard');

$count = $params->get('news');

$target = $params->get('target');

$logo = $params->get('image');

$showall = $params->get('showall');


$db =& JFactory::getDBO();

$from	= "#__realpin_items";

if($showall == 1)
$query = "SELECT * FROM ".$from." WHERE created>=DATE_ADD(NOW(), INTERVAL -100 DAY) AND published='1' ORDER BY created DESC";
else
$query = "SELECT * FROM ".$from." WHERE created>=DATE_ADD(NOW(), INTERVAL -100 DAY) AND published='1' AND pinboard = '$pinboard' ORDER BY created DESC";
  
$db->setQuery($query);  
	  
$data=$db->loadObjectList();

if(!function_exists('substru'))
{ 
	function substru($str,$from,$len)
	{
		return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'. $from .'}'.'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'. $len .'}).*#s','$1', $str);
	}
}

?>

<?php

if($logo=="default")
{
echo JHTML::_('image', 'components/com_realpin/includes/css/admin/logo_news.png', 'logo_news.png');
}
elseif($logo=="pro")
{
echo JHTML::_('image', 'modules/mod_realpin_news/logo.png', 'logo.png');
}


?>

<ul class="mostread">
<?php

$i=0;
foreach ($data as $datas)
{
	
		if($i==$count)
		{
			break;
		}	

$from2	= "#__menu";

$query2 = "SELECT id FROM ".$from2." WHERE link LIKE '%index.php?option=com_realpin&view=rpdefault&pinboard=".$datas->pinboard."%'";
  
$db->setQuery($query2);  
	  
$itemid=$db->loadResult();

?>


	   <li class="mostread<?php echo $params->get('moduleclass_sfx'); ?>">
		<a href="<?php echo JRoute::_('index.php?option=com_realpin&pinboard='.$datas->pinboard.'&Itemid='.$itemid.'&rpitem='.$datas->id); ?>" class="mostread<?php echo $params->get('moduleclass_sfx'); ?>" target="<?php echo $target; ?>">
		<?php 
		
		if($i!=$count)
		{
			$title=$datas->title;
			if($title=="-" or $title=="")
			{
				if($datas->type=="youtube")
			    {
				$tmp=JText::_('LANG_NEW_OPT_VIDEO2');
				}
				
				if($datas->type=="postit")
			    {	
				    if($datas->text=="" or $datas->text=="-")
					{
					$tmp=JText::_('LANG_NEW_OPT_POSTIT2');
					}
					else
					{
					$tmp=$datas->text;
					}
				}
				
				if($datas->type=="pic")
			    {
				$tmp=JText::_('LANG_NEW_OPT_IMAGE2');
				}
				
				$result=$tmp;	
			}	
			else
			{
				$result=$title;
			}
		echo $result;	
        $i++;
		}
		
		?>
        </a>
	</li>
<?php }
if ($i == 0) {
echo "<li><p>Keine News auf der Pinnwand...</p></li>";
}

  ?>
</ul>

