<?php
$blogid = get_current_blog_id();
if ($blogid == 20) {
    //News Mongabay
    mongabay_series_section (array('forest-trackers','oceans','amazon-conservation','land-rights-and-extractives','endangered-environmentalists','indonesias-forest-guardians','conservation-effectiveness','southeast-asian-infrastructure'), 4);
}
if ($blogid == 25) {
    //Mongabay Spanish
    mongabay_series_section (array('especiales-transnacionales','ambientalistas-amenazados','pueblos-indigenas-frente-al-covid-19','especial-tecnologia-para-cazar-delitos','conservacion-en-oceanos','la-ruta-de-la-flota-china-en-latinoamerica','el-gran-chaco','comunidades-forestales-en-mexico'), 3);
}
if ($blogid == 30) {
    //Mongabay India
    mongabay_series_section (array('wetland-champions','environment-and-health','almost-famous-species','eco-hope','iconic-india-landscapes','beyond-protected-areas','conserving-agrobiodiversity','just-transitions'), 4);
}
?>