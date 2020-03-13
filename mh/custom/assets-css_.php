<?php
$htmlHead = '<!doctype html>';
$htmlHead .= '<html>';
$htmlHead .= '<head>';
$htmlHead .= '<meta charset="utf-8">';
$htmlHead .= '<style type="text/css">
@font-face{
	font-family: Hero;
	src: url("http://193.110.207.21/externaltools/TPHero/TPHero-Regular.otf") format("opentype");
}
@font-face{
	font-family: HeroItalic;
	src: url("http://193.110.207.21/externaltools/TPHero/TPHero-RegularItalic.otf") format("opentype");
	font-style: italic;
}
@font-face{
	font-family: HeroSemiBold;
	src: url("http://193.110.207.21/externaltools/TPHero/TPHero-SemiBold.otf") format("opentype");
	font-weight: 700;
}
@font-face{
	font-family: HeroSemiBoldItalic;
	src: url("http://193.110.207.21/externaltools/TPHero/TPHero-SemiBoldItalic.otf") format("opentype");
	font-weight: 700;
	font-style: italic;
}
body {
	margin: 0mm;
	padding: 0mm;
	font-family: Hero, Arial, Helvetica;
	color: #000000;
	line-height: 100%;
}';
if(isset($rowBf['width'])){
	$htmlHead .= '
		body {
			width: ' . $rowBf['width'] . 'px;
			height: ' . $rowBf['height'] . 'px;
			background-image: url("http://193.110.207.21' . $CONFIG['system']['directoryInstallation'] . 'media/' . $rowB['filesys_filename'] . '");
			background-repeat: no-repeat;
		}
';
}

$htmlHead .= '
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

.componentPartnerlogo,
.componentUploadfile {
	width: 100%;
	height: 100%;
	display: block;
}
.componentPartnerlogo img,
.componentUploadfile img {
	display: block;
	max-width: 100%;
	max-height: 100%;
	width: auto;
	height: auto;
}
.componentProductimage {
	width: 100%;
	height: 100%;
}
.componentProductimage img {
    display: block;
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
}
.aligncenter .componentProductimage img {
	margin-left: auto;
	margin-right: auto;
}
.alignright .componentProductimage img {
	margin-left: auto;
	margin-right: 0;
}

.compboxOuter {
	position: absolute;
	border: 1px dashed transparent;
	border-left-width: 2px;
	overflow: hidden;
	font-weight: normal;
	line-height: 180%;
}
.compboxOuter .content {
	width: 100%;
	height: 100%;
}
.compboxOuter p,
.compboxOuter .content p {
	margin: 0 0 1em 0;
}
.compboxOuter p span,
.compboxOuter .content p span {
	line-height: 175%;
}
/* Banner - Textfield */
.comboxOuter_1_1 {
	line-height: 150%;
}
/* Banner - Short Description */
.comboxOuter_1_5 {
	line-height: 150%;
}
/* Banner - 25 Description */
.comboxOuter_1_6 {
	line-height: 150%;
}
/* Banner - 50 Description */
.comboxOuter_1_7 {
	line-height: 150%;
}
/* Banner - 100 Description */
.comboxOuter_1_8 {
	line-height: 150%;
}
</style>
</head>';

?>