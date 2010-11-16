<?php
/*
Plugin Name: WP XHProfiler
Plugin URI: 
Description: Profile Wordpress using Xhprof
Version: 1.0.0
Author: Erwin Atuli
Author URI: http://erwin-atuli.com
*/

define('WP_XHPROF_BASENAME', plugin_basename( __FILE__ ));
define('WP_XHPROF_URL', WP_PLUGIN_URL."/".str_replace(basename(WP_XHPROF_BASENAME), '', WP_XHPROF_BASENAME));

add_action('plugins_loaded', 'wp_xhprof_start');
function wp_xhprof_start() {
    include_once dirname(__FILE__).'/xhprof_lib/utils/xhprof_lib.php';
    include_once dirname(__FILE__).'/xhprof_lib/utils/xhprof_runs.php';
    $profile_cpu_n_mem = XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY;
    xhprof_enable();
}
add_action('shutdown', 'wp_xhprof_end');
function wp_xhprof_end() {
    $profiler_namespace = 'wp_profiling';  // namespace for your application
    $xhprof_data = xhprof_disable();
    $xhprof_runs = new XHProfRuns_Default();
    $run_id = $xhprof_runs->save_run($xhprof_data, $profiler_namespace);
 
    // url to the XHProf UI libraries (change the host name and path)
    $profiler_url = sprintf(WP_XHPROF_URL.'xhprof_html/index.php?run=%s&source=%s', $run_id, $profiler_namespace);
    echo $profiler_url;
}