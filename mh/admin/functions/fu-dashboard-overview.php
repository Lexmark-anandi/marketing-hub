<?php
$out = array();
$out['content'] = '<div id="modulOuter">';

$modulpath = $CONFIG['activeSettings']['id_page'] .$CONFIG['system']['delimiterPathAttr'] . '0' . $CONFIG['system']['delimiterPathAttr'] . $CONFIG['page']['id_mod'];

// grid
$out['content'] = '<div class="dashboardOuter gridster">';
$out['content'] .= '<ul></ul>';
$out['content'] .= '</div>';



// Pager
$out['content'] .= '<div id="dashboardPager" class="gridPager gridPagerFix">';
$out['content'] .= '<div id="gridPager_' . $modulpath . '_left" class="gridPagerInner gridPagerLeft">';
$out['content'] .= '<div class="modulIcon pagerSettings" title=""><i class="fa fa-sliders"></i></div>';
$out['content'] .= '</div>';
$out['content'] .= '</div>';



$out['content'] .= '</div>';






echo $out['content'];

?>