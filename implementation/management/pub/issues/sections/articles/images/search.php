<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/common.php');
load_common_include_files("$ADMIN_DIR/pub/issues/sections/articles/images");
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Publication.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Issue.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Section.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Article.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Language.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Image.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/ImageSearch.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Input.php');
require_once($_SERVER['DOCUMENT_ROOT']."/$ADMIN_DIR/CampsiteInterface.php");
require_once($_SERVER['DOCUMENT_ROOT']."/$ADMIN_DIR/imagearchive/include.inc.php");

list($access, $User) = check_basic_access($_REQUEST);
if (!$access) {
	header("Location: /$ADMIN/logout.php");
	exit;
}

$OrderBy = Input::Get('order_by', 'string', 'id', true);
$OrderDirection = Input::Get('order_direction', 'string', 'ASC', true);
$view = Input::Get('view', 'string', 'thumbnail', true);
$ImageOffset = Input::Get('image_offset', 'int', 0, true);
$SearchDescription = Input::Get('search_description', 'string', '', true);
$SearchPhotographer = Input::Get('search_photographer', 'string', '', true);
$SearchPlace = Input::Get('search_place', 'string', '', true);
$SearchDate = Input::Get('search_date', 'string', '', true);
$SearchInUse = Input::Get('search_inuse', 'string', '', true);
$SearchUploadedBy = Input::Get('search_uploadedby', 'int', '', true);
	
$Pub = Input::Get('Pub', 'int', 0);
$Issue = Input::Get('Issue', 'int', 0);
$Section = Input::Get('Section', 'int', 0);
$Language = Input::Get('Language', 'int', 0);
$sLanguage = Input::Get('sLanguage', 'int', 0);
$Article = Input::Get('Article', 'int', 0);

if (!Input::IsValid()) {
	CampsiteInterface::DisplayError(array('Invalid input: $1', Input::GetErrorString()), $_SERVER['REQUEST_URI']);
	exit;	
}

$imageNav =& new ImageNav(CAMPSITE_IMAGEARCHIVE_IMAGES_PER_PAGE, $view);
$publicationObj =& new Publication($Pub);
$issueObj =& new Issue($Pub, $Language, $Issue);
$sectionObj =& new Section($Pub, $Issue, $Language, $Section);
$languageObj =& new Language($Language);
$articleObj =& new Article($Pub, $Issue, $Section, $sLanguage, $Article);

///////////////////////////////////////////////////////////////////////
$ImagesPerPage = 8;

// build the links for ordering (search results) //////////////////////
if ($OrderDirection == 'DESC') {
	$ReverseOrderDirection = "ASC";
	$OrderSign = "<img src=\"/$ADMIN/img/icon/search_order_direction_down.png\" border=\"0\">";
} else {
	$ReverseOrderDirection = "DESC";
	$OrderSign = "<img src=\"/$ADMIN/img/icon/search_order_direction_up.png\" border=\"0\">";
}

$IdHref  = 
	CampsiteInterface::ArticleUrl($articleObj, $Language, 'images/search.php')
	.'&order_by=id'
	.$imageNav->getKeywordSearchLink();
$DescriptionHref  = 
	CampsiteInterface::ArticleUrl($articleObj, $Language, 'images/search.php')
	.'&order_by=description'
	.$imageNav->getKeywordSearchLink();
$PhotographerHref  = 
	CampsiteInterface::ArticleUrl($articleObj, $Language, 'images/search.php')
	.'&order_by=photographer'
	.$imageNav->getKeywordSearchLink();
$PlaceHref  = 
	CampsiteInterface::ArticleUrl($articleObj, $Language, 'images/search.php')
	.'&order_by=place'
	.$imageNav->getKeywordSearchLink();
$DateHref  = 
	CampsiteInterface::ArticleUrl($articleObj, $Language, 'images/search.php')
	.'&order_by=date'
	.$imageNav->getKeywordSearchLink();
$InUseHref = 
	CampsiteInterface::ArticleUrl($articleObj, $Language, 'images/search.php')
	.'&order_by=inuse'
	.$imageNav->getKeywordSearchLink();
///////////////////////////////////////////////////////////////////////
$DescriptionOrderIcon = '';
$PhotographerOrderIcon = '';
$PlaceOrderIcon = '';
$DateOrderIcon = '';
$InUseOrderIcon = '';
$IdOrderIcon = '';
switch ($OrderBy) {
case 'description':
	$DescriptionOrderIcon = $OrderSign;
	$DescriptionHref .= '&order_direction='.$ReverseOrderDirection;
	break;
case 'photographer':
	$PhotographerOrderIcon = $OrderSign;
	$PhotographerHref .= '&order_direction='.$ReverseOrderDirection;
	break;
case 'place':
	$PlaceOrderIcon = $OrderSign;
	$PlaceHref .= '&order_direction='.$ReverseOrderDirection;
	break;
case 'date':
	$DateOrderIcon = $OrderSign;
	$DateHref .= '&order_direction='.$ReverseOrderDirection;
	break;
case 'inuse':
	$InUseOrderIcon = $OrderSign;
	$InUseHref .= '&order_direction='.$ReverseOrderDirection;
	break;
case 'id':
	$IdOrderIcon = $OrderSign;
	$IdHref .= '&order_direction='.$ReverseOrderDirection;
	break;
}
///////////////////////////////////////////////////////////////////////

$TotalImages = Image::GetTotalImages();
$imageSearch =& new ImageSearch(CAMPSITE_IMAGEARCHIVE_IMAGES_PER_PAGE);
$imageSearch->run();
$imageData =& $imageSearch->getImages();
$NumImagesFound = $imageSearch->getNumImagesFound();
$uploadedByUsers =& Image::GetUploadUsers();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
	"http://www.w3.org/TR/REC-html40/loose.dtd">
<HTML>
<HEAD>
	<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<META HTTP-EQUIV="Expires" CONTENT="now">
	<TITLE><?php  putGS('Images'); ?></TITLE>
	<LINK rel="stylesheet" type="text/css" href="<?php echo $Campsite['WEBSITE_URL']; ?>/css/admin_stylesheet.css">
	<script type="text/javascript" src="<?php echo $Campsite['WEBSITE_URL']; ?>/javascript/fValidate/fValidate.config.js"></script>
    <script type="text/javascript" src="<?php echo $Campsite['WEBSITE_URL']; ?>/javascript/fValidate/fValidate.core.js"></script>
    <script type="text/javascript" src="<?php echo $Campsite['WEBSITE_URL']; ?>/javascript/fValidate/fValidate.lang-enUS.js"></script>
    <script type="text/javascript" src="<?php echo $Campsite['WEBSITE_URL']; ?>/javascript/fValidate/fValidate.validators.js"></script>	
</HEAD>
<BODY>

<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="1" WIDTH="100%" class="page_title_container">
<TR>
	<TD class="page_title">
		<?php putGS('Link Image to Article'); ?>
	</TD>
	<TD ALIGN="RIGHT">
	 	<TABLE BORDER="0" CELLSPACING="1" CELLPADDING="0">
		<TR>
			<TD><A HREF="/<?php echo $ADMIN; ?>/pub/issues/sections/articles/images/?Pub=<?php  p($Pub); ?>&Issue=<?php  p($Issue); ?>&Article=<?php  p($Article); ?>&Language=<?php  p($Language); ?>&sLanguage=<?php  p($sLanguage); ?>&Section=<?php  p($Section); ?>" class="breadcrumb"><?php  putGS("Images");  ?></A></TD>
			<td class="breadcrumb_separator">&nbsp;</td>
			<TD><A HREF="/<?php echo $ADMIN; ?>/pub/issues/sections/articles/?Pub=<?php  p($Pub); ?>&Issue=<?php  p($Issue); ?>&Language=<?php  p($Language); ?>&Section=<?php  p($Section); ?>" class="breadcrumb"><?php  putGS("Articles");  ?></A></TD>
			<td class="breadcrumb_separator">&nbsp;</td>
			<TD><A HREF="/<?php echo $ADMIN; ?>/pub/issues/sections/?Pub=<?php  p($Pub); ?>&Issue=<?php  p($Issue); ?>&Language=<?php  p($Language); ?>" class="breadcrumb"><?php  putGS("Sections");  ?></A></TD>
			<td class="breadcrumb_separator">&nbsp;</td>
			<TD><A HREF="/<?php echo $ADMIN; ?>/pub/issues/?Pub=<?php  p($Pub); ?>" class="breadcrumb"><?php  putGS("Issues");  ?></A></TD>
			<td class="breadcrumb_separator">&nbsp;</td>
			<TD><A HREF="/<?php echo $ADMIN; ?>/pub/" class="breadcrumb"><?php  putGS("Publications");  ?></A></TD>
		</TR>
	</TABLE>
	</TD>
</TR>
</TABLE>

<TABLE BORDER="0" CELLSPACING="1" CELLPADDING="1" WIDTH="100%" class="current_location_table">
<TR>
	<TD ALIGN="RIGHT" WIDTH="1%" NOWRAP VALIGN="TOP" class="current_location_title">&nbsp;<?php  putGS("Publication"); ?>:</TD>
	<TD VALIGN="TOP" class="current_location_content"><?php echo htmlspecialchars($publicationObj->getName()); ?></TD>

	<TD ALIGN="RIGHT" WIDTH="1%" NOWRAP VALIGN="TOP" class="current_location_title">&nbsp;<?php  putGS("Issue"); ?>:</TD>
	<TD VALIGN="TOP" class="current_location_content"><?php echo $issueObj->getIssueId(); ?>. <?php  echo htmlspecialchars($issueObj->getName()); ?> (<?php echo $languageObj->getName(); ?>)</TD>

	<TD ALIGN="RIGHT" WIDTH="1%" NOWRAP VALIGN="TOP" class="current_location_title">&nbsp;<?php  putGS("Section"); ?>:</TD>
	<TD VALIGN="TOP" class="current_location_content"><?php echo $sectionObj->getSectionId(); ?>. <?php  echo htmlspecialchars($sectionObj->getName());; ?></TD>

	<TD ALIGN="RIGHT" WIDTH="1%" NOWRAP VALIGN="TOP" class="current_location_title">&nbsp;<?php  putGS("Article"); ?>:</TD>
	<TD VALIGN="TOP" class="current_location_content"><?php echo htmlspecialchars($articleObj->getTitle()); ?></TD>
</TR>
</TABLE>

<table>
<tr>
    <td><?php echo CampsiteInterface::ArticleLink($articleObj, $Language, 'images/index.php') ?><IMG SRC="/<?php echo $ADMIN; ?>/img/icon/back.png" BORDER="0" ALT="<?php putGS("Back to Article Image List"); ?>"></a></td>
    <td><?php echo CampsiteInterface::ArticleLink($articleObj, $Language, 'images/index.php') ?><b><?php echo putGS('Back to Article Image List'); ?></b></a></td>
    <td><?php echo CampsiteInterface::ArticleLink($articleObj, $Language, 'edit.php') ?><IMG SRC="/<?php echo $ADMIN; ?>/img/icon/back.png" BORDER="0" ALT="<?php putGS("Back to article details"); ?>"></a></td>
    <td><?php echo CampsiteInterface::ArticleLink($articleObj, $Language, 'edit.php') ?><b><?php echo putGS('Back to article details'); ?></b></a></td>
</tr>
<tr>
    <td><?php echo CampsiteInterface::ArticleLink($articleObj, $Language, 'images/search.php') ?><IMG SRC="/<?php echo $ADMIN; ?>/img/icon/reset.png" BORDER="0" ALT="<?php putGS("Reset search conditions"); ?>"></a></td>
    <td colspan="3"><?php echo CampsiteInterface::ArticleLink($articleObj, $Language, 'images/search.php') ?><b><?php echo putGS('Reset search conditions'); ?></b></a></td>
</tr>
</table>

<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="3" class="table_input" style="margin-bottom: 10px; margin-top: 5px;" align="center">
<form method="POST" action="search.php">
<input type="hidden" name="order_by" value="<?php echo $OrderBy; ?>">
<input type="hidden" name="order_direction" value="<?php echo $OrderDirection; ?>">
<input type="hidden" name="view" value="<?php echo $view; ?>">
<input type="hidden" name="image_offset" value="0">
<input type="hidden" name="Pub" value="<?php p($Pub); ?>">
<input type="hidden" name="Issue" value="<?php p($Issue); ?>">
<input type="hidden" name="Section" value="<?php p($Section); ?>">
<input type="hidden" name="Language" value="<?php p($Language); ?>">
<input type="hidden" name="sLanguage" value="<?php p($sLanguage); ?>">
<input type="hidden" name="Article" value="<?php p($Article); ?>">
<tr>
	<td style="padding-left: 10px;"><?php putGS('Description')?>:</td>
	<td><input type="text" name="search_description" value="<?php echo $SearchDescription; ?>" class="input_text" style="width: 150px;"></td>
	<td><?php putGS('Photographer'); ?>:</td>
	<td><input type="text" name="search_photographer" value="<?php echo $SearchPhotographer; ?>" class="input_text" style="width: 100px;"></td>
	<td><?php putGS('Place'); ?>:</td>
	<td><input type="text" name="search_place" value="<?php echo $SearchPlace; ?>" class="input_text" style="width: 100px;"></td>
	<td ><?php putGS('Date'); ?>:</td>
	<td><input type="text" name="search_date" value="<?php echo $SearchDate; ?>" class="input_text" style="width: 80px;"></td>
	<td nowrap>Uploaded by:</td>
	<td>
		<select name="search_uploadedby" class="input_select" style="width: 100px;">
		<option value="0"></option>
		<?php 
		foreach ($uploadedByUsers as $tmpUser) {
			?>
			<option value="<?php echo $tmpUser->getId(); ?>" <?php if ($tmpUser->getId() == $SearchUploadedBy)  { echo "selected"; } ?>><?php echo htmlspecialchars($tmpUser->getName()); ?></option>
			<?php
		}
		?>
		</select>
	</td>
	<td><input type="submit" name="submit_button" value="Search" class="button"></td>
</tr>
<tr>
	<td colspan="11" align="center" >
		Additional searches: &nbsp;
		<a href="<?php echo CampsiteInterface::ArticleUrl($articleObj, $Language, 'images/search.php').'&'.$imageNav->getSearchLink(); ?>&order_by=time_created" style="font-size: 9pt; font-weight: bold; text-decoration: underline;"><?php putGS('Most Recently Added'); ?></a><?php if ($OrderBy == "time_created") { echo "*"; } ?>
		&nbsp;
		<a href="<?php echo CampsiteInterface::ArticleUrl($articleObj, $Language, 'images/search.php').'&'.$imageNav->getSearchLink(); ?>&order_by=last_modified" style="font-size: 9pt; font-weight: bold; text-decoration: underline;"><?php putGS('Most Recently Modified'); ?></a><?php if ($OrderBy == "last_modified") { echo "*"; } ?>
	</td>
	
</tr>
</form>
</table>

<?php
if (count($imageData) > 0) {
   	include('view_thumbnail.inc.php');
}

CampsiteInterface::CopyrightNotice(); ?>