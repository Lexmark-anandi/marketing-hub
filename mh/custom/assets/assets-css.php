<?php
$htmlHead = '<!doctype html>';
$htmlHead .= '<html>';
$htmlHead .= '<head>';
$htmlHead .= '<meta charset="utf-8">';
$htmlHead .= '<style type="text/css">
@font-face{ 
	font-family: Hero;
	src: url("https://qashrwrtapp001.lex1.lexmark.com/externaltools/TPHero/TPHero-Regular.otf") format("opentype");
}
@font-face{
	font-family: HeroItalic;
	src: url("https://qashrwrtapp001.lex1.lexmark.com/externaltools/TPHero/TPHero-RegularItalic.otf") format("opentype");
	font-style: italic;
}
@font-face{
	font-family: HeroSemiBold;
	src: url("https://qashrwrtapp001.lex1.lexmark.com/externaltools/TPHero/TPHero-SemiBold.otf") format("opentype");
	font-weight: 700;
}
@font-face{
	font-family: HeroSemiBoldItalic;
	src: url("https://qashrwrtapp001.lex1.lexmark.com/externaltools/TPHero/TPHero-SemiBoldItalic.otf") format("opentype");
	font-weight: 700;
	font-style: italic;
}
body {
	margin: 0mm;
	padding: 0mm;
	font-family: Hero, Arial, Helvetica;
	color: #000000;
	/**line-height: 100%;**/
}';
if(isset($rowBf['width'])){
	$htmlHead .= '
.bannerpage {
	position: relative;
	width: ' . $rowBf['width'] . 'px;
	height: ' . $rowBf['height'] . 'px;
	background-image: url("https://qashrwrtapp001.lex1.lexmark.com' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rowB['filesys_filename'] . '");
	background-repeat: no-repeat;
	overflow: hidden;
}
';
}

$htmlHead .= '
h1, h2, h3, h4, h5, h6, p, ul, li, div, input, select, textarea, button, a {
	box-sizing: border-box;
	outline: 0;
}

.dummyPartnercontact {
}
.dummyPartnercontact span {
	display: block;
}
.contactalignleft .dummyPartnercontact span {
	margin-bottom: 10px;
}
.contactaligncenter .dummyPartnercontact span {
}
.contactalignright .dummyPartnercontact span {
	margin-bottom: 10px;
}
.dummyPartnercontact  span span {
	margin-right: 10px;
	display: inline;
}
.contactalignleft .dummyPartnercontact span span {
	display: block;
	margin-bottom: 0px;
}
.contactaligncenter .dummyPartnercontact span span {
}
.contactalignright .dummyPartnercontact span span {
	display: block;
	margin-bottom: 0px;
}
.contactaligncenter .contactDelimiter:after {
	content: "-";
}

.partnerContactCombination {
	position: relative;
	overflow: hidden;
	height: 100%;
	/**line-height: 145%;**/
}
.partnerContactCombination .componentPartnerlogo {
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	width: 30%;
}
.partnerContactCombination .dummyPartnercontact {
	position: absolute;
	top: 0;
	right: 0;
	bottom: 0;
	width: 65%;
}

.componentProductimage,
.componentPartnerlogo,
.componentUploadfile {
	width: 100%;
	height: auto;
	display: block;
}
.componentPartnerlogo {
	padding: 4px;
}

.componentProductimage img,
.componentPartnerlogo img,
.componentUploadfile img {
	display: block;
	max-width: 100%;
	max-height: 100%;
	width: auto;
	height: auto;
}

.alignleft .componentProductimage img,
.alignleft .componentPartnerlogo img,
.alignleft .componentUploadfile img {
	margin-left: 0;
	margin-right: auto;
}
.aligncenter .componentProductimage img,
.aligncenter .componentPartnerlogo img,
.aligncenter .componentUploadfile img {
	margin-left: auto;
	margin-right: auto;
}
.alignright .componentProductimage img,
.alignright .componentPartnerlogo img,
.alignright .componentUploadfile img {
	margin-left: auto;
	margin-right: 0;
}

.verticalaligntop .verticalalignbox,
.verticalaligntop > .dummyPartnercontact,
.verticalaligntop .componentProductimage,
.verticalaligntop .componentPartnerlogo,
.verticalaligntop .componentUploadfile {
	position: relative;
	top: 0%;
	-webkit-transform: translateY(-0%);
	-ms-transform: translateY(-0%);
	transform: translateY(-0%);
}
.verticalalignmiddle .verticalalignbox,
.verticalalignmiddle > .dummyPartnercontact,
.verticalalignmiddle .componentProductimage,
.verticalalignmiddle .componentPartnerlogo,
.verticalalignmiddle .componentUploadfile {
	position: relative;
	top: 50%;
	-webkit-transform: translateY(-50%);
	-ms-transform: translateY(-50%);
	transform: translateY(-50%);
}
.verticalalignbottom .verticalalignbox,
.verticalalignbottom > .dummyPartnercontact,
.verticalalignbottom .componentProductimage,
.verticalalignbottom .componentPartnerlogo,
.verticalalignbottom .componentUploadfile {
	position: relative;
	top: 100%;
	-webkit-transform: translateY(-100%);
	-ms-transform: translateY(-100%);
	transform: translateY(-100%);
}



.compboxOuter {
	position: absolute;
	border: 1px dashed transparent;
	border-left-width: 2px;
	overflow: hidden;
	font-weight: normal;
	/**line-height: 180%;**/
	line-height: 1.5;
	display: block;
}
.compboxOuter[data-tcid="17"] {
    /**line-height: 145%;**/
}
.editBanner .compboxOuter {
	/**line-height: 145%;**/
}
.compboxOuter .content {
	width: 100%;
	height: 100%;
	grid-template-rows: minmax(1px,1fr);
}
.compboxOuter p,
.compboxOuter .content p {
	/**margin: 0 0 1em 0;**/
}
.compboxOuter p span,
.compboxOuter .content p span {
	/**line-height: 175%;**/
}
.editBanner .compboxOuter p span,
.editBanner .compboxOuter .content p span {
	/**line-height: 145%;**/
}
/* Banner - Textfield 
.comboxOuter_1_1 {
	line-height: 150%;
}*/
/* Banner - Short Description 
.comboxOuter_1_5 {
	line-height: 150%;
}*/
/* Banner - 25 Description 
.comboxOuter_1_6 {
	line-height: 150%;
}*/
/* Banner - 50 Description 
.comboxOuter_1_7 {
	line-height: 150%;
}*/
/* Banner - 100 Description 
.comboxOuter_1_8 {
	line-height: 150%;
}*/
</style>
</head>';

?>