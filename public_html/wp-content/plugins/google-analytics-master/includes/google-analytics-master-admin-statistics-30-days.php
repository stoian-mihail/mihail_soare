<?php
// Create menu
function menu_single_google_analytics_admin_statistics_30_days(){
	if ( is_admin() )
	add_submenu_page( 'google-analytics-master', 'Statistics 30 Days', 'Statistics 30 Days', 'manage_options', 'google-analytics-master-admin-statistics-30-days', 'google_analytics_master_admin_statistics_30_days' );
}

function google_analytics_master_admin_statistics_30_days(){
?>
<div class="wrap">
<h2>Statistics 30 Days</h2>
<br>

<!-- This code snippet checks if Client Id is set in wordpress -->
<?php 
if(is_multisite()){
	$google_analytics_master_name = get_site_option('google_analytics_master_name');
	$google_analytics_master_client_id = get_site_option('google_analytics_master_client_id');
	if(empty($google_analytics_master_client_id)){
		echo '<div class="notice notice-error is-dismissible">';
		printf (__('<h3>Warning!!!</h3><p> Go to '.$google_analytics_master_name.' -> Settings page and insert your Google Client ID.</p>'));
		echo '<p><a href="https://console.developers.google.com" target="_blank">Get Google Analytics OAuth 2.0 Credentials -> Client ID</a></p><br>';
		echo '</div>';
	}
}
else{
	$google_analytics_master_name = get_option('google_analytics_master_name');
	$google_analytics_master_client_id = get_option('google_analytics_master_client_id');
	if(empty($google_analytics_master_client_id)){
		echo '<div class="notice notice-error is-dismissible">';
		printf (__('<h3>Warning!!!</h3><p> Go to '.$google_analytics_master_name.' -> Settings page and insert your Google Client ID.</p>'));
		echo '<p><a href="https://console.developers.google.com" target="_blank">Get Google Analytics OAuth 2.0 Credentials -> Client ID</a></p><br>';
		echo '</div>';
	}
}

if(!empty($google_analytics_master_client_id)){
?>
<!-- START ANALYTICS EMBED -->
<!DOCTYPE html>
<meta charset="utf-8">

<script>
(function(w,d,s,g,js,fs){
  g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
  js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
  js.src='https://apis.google.com/js/platform.js';
  fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
}(window,document,'script'));
</script>

<div class="Dashboard Dashboard--full">
  <header class="Dashboard-header">
	<div id="embed-api-auth-container"></div>
    <ul class="FlexGrid">
      <li class="FlexGrid-item">
        <div class="Titles">
          <h1 class="Titles-main" id="view-name">Select a View</h1>
          <div class="Titles-sub">Various visualizations</div>
        </div>
      </li>
      <li class="FlexGrid-item FlexGrid-item--fixed">
        <div id="active-users-container"></div>
      </li>
    </ul>
    <div id="view-selector-container" style="display: flex;"></div>
  </header>

  <ul class="FlexGrid FlexGrid--halves">
    <li class="FlexGrid-item">
        <header class="Titles">
          <h1 class="Titles-main">Sessions</h1>
          <div class="Titles-sub">Last 30 days</div>
        </header>
    <div id="chart-container"></div>
    </li>
    <li class="FlexGrid-item">
	  <div class="Chartjs">
        <header class="Titles">
          <h1 class="Titles-main">Top Countries by Sessions</h1>
          <div class="Titles-sub">Last 30 days</div>
        </header>
        <figure class="Chartjs-figure" id="chart-1-container"></figure>
        <ol class="Chartjs-legend" id="legend-1-container"></ol>
	  </div>
    </li>
	<li class="FlexGrid-item">
      <div class="Chartjs">
        <header class="Titles">
          <h1 class="Titles-main">Top Operating Systems</h1>
          <div class="Titles-sub">Last 30 days</div>
        </header>
        <figure class="Chartjs-figure" id="os-chart-container"></figure>
        <ol class="Chartjs-legend" id="legend-2-container"></ol>
      </div>
    </li>
    <li class="FlexGrid-item">
      <div class="Chartjs">
        <header class="Titles">
          <h1 class="Titles-main">Top Referals</h1>
          <div class="Titles-sub">Last 30 days</div>
        </header>
        <figure class="Chartjs-figure" id="referer-chart-container"></figure>
        <ol class="Chartjs-legend" id="legend-3-container"></ol>
      </div>
    </li>
    <li class="FlexGrid-item">
      <div class="Chartjs">
        <header class="Titles">
          <h1 class="Titles-main">Top Browsers</h1>
          <div class="Titles-sub">Last 30 days</div>
        </header>
        <figure class="Chartjs-figure" id="main-chart-container"></figure>
        <ol class="Chartjs-legend" id="legend-4-container"></ol>
      </div>
    </li>
	<li class="FlexGrid-item">
      <div class="Chartjs">
        <header class="Titles">
          <h1 class="Titles-main">Top Languages</h1>
          <div class="Titles-sub">Last 30 days</div>
        </header>
        <figure class="Chartjs-figure" id="lang-chart-container"></figure>
        <ol class="Chartjs-legend" id="legend-5-container"></ol>
      </div>
    </li>
  </ul>
  
<div style="z-index: 1; position: relative;"><h3>Want to customize the display or add more metrics? Get in touch with us via <a href="https://wordpress.techgasp.com/support" target="_blank" title="Visit Website">Ticket</a></h3></div>

</div>

<!-- Include the ViewSelector2 component script. -->
<script src="<?php echo plugins_url('public/javascript/embed-api/components/view-selector2.js', __FILE__); ?>"></script>

<!-- Include the DateRangeSelector component script. -->
<script src="<?php echo plugins_url('public/javascript/embed-api/components/date-range-selector.js', __FILE__); ?>"></script>

<!-- Include the ActiveUsers component script. -->
<script src="<?php echo plugins_url('public/javascript/embed-api/components/active-users.js', __FILE__); ?>"></script>

<!-- Include the CSS that styles the charts. -->
<link rel="stylesheet" href="<?php echo plugins_url('public/css/index.css', __FILE__); ?>">
<link rel="stylesheet" href="<?php echo plugins_url('public/css/normalize.css', __FILE__); ?>">
<link rel="stylesheet" href="<?php echo plugins_url('public/css/chartjs-visualizations.css', __FILE__); ?>">

<script>

// == NOTE ==
// This code uses ES6 promises. If you want to use this code in a browser
// that doesn't supporting promises natively, you'll have to include a polyfill.

gapi.analytics.ready(function() {

  /**
   * Authorize the user immediately if the user has already granted access.
   * If no access has been created, render an authorize button inside the
   * element with the ID "embed-api-auth-container".
   */
  gapi.analytics.auth.authorize({
    container: 'embed-api-auth-container',
    clientid: '<?php 
if(is_multisite()){
    echo get_site_option('google_analytics_master_client_id');
}
else{
    echo get_option('google_analytics_master_client_id');
}
?>',
  });


  /**
   * Create a new ViewSelector instance to be rendered inside of an
   * element with the id "view-selector-container".
   */
  var viewSelector = new gapi.analytics.ViewSelector({
    container: 'view-selector-container'
  });

  // Render the view selector to the page.
  viewSelector.execute();


  /**
   * Create a new DataChart instance with the given query parameters
   * and Google chart options. It will be rendered inside an element
   * with the id "chart-container".
   */
  var dataChart = new gapi.analytics.googleCharts.DataChart({
    query: {
      metrics: 'ga:sessions',
      dimensions: 'ga:date',
      'start-date': '30daysAgo',
      'end-date': 'yesterday'
    },
    chart: {
      container: 'chart-container',
      type: 'LINE',
      options: {
        width: '100%'
      }
    }
  });

/**
   * Create the first DataChart for top countries over the past 30 days.
   * It will be rendered inside an element with the id "chart-1-container".
   */
  var dataChart1 = new gapi.analytics.googleCharts.DataChart({
    query: {
      metrics: 'ga:sessions',
      dimensions: 'ga:country',
      'start-date': '30daysAgo',
      'end-date': 'yesterday',
      'max-results': 10,
      sort: '-ga:sessions'
    },
    chart: {
      container: 'chart-1-container',
      type: 'PIE',
      options: {
        width: '100%',
        pieHole: 4/9
      }
    }
  });

   /**
   * Create a table chart showing top browsers for users to interact with.
   * Clicking on a row in the table will update a second timeline chart with
   * data from the selected browser.
   */ 
var mainChart = new gapi.analytics.googleCharts.DataChart({
    query: {
      'dimensions': 'ga:browser',
      'metrics': 'ga:sessions',
      'start-date': '30daysAgo',
      'end-date': 'yesterday',
      'sort': '-ga:sessions',
      'max-results': '7'
    },
    chart: {
      type: 'TABLE',
      container: 'main-chart-container',
      options: {
        width: '100%'
      }
    }
  });

var refererChart = new gapi.analytics.googleCharts.DataChart({
    query: {
      'dimensions': 'ga:fullReferrer',
      'metrics': 'ga:sessions',
      'start-date': '30daysAgo',
      'end-date': 'yesterday',
      'sort': '-ga:sessions',
      'max-results': '7'
    },
    chart: {
	  container: 'referer-chart-container',
      type: 'PIE',
      options: {
        width: '100%',
        pieHole: 4/9
      }
    }
  });
  
var osChart = new gapi.analytics.googleCharts.DataChart({
    query: {
      'dimensions': 'ga:operatingSystem',
      'metrics': 'ga:sessions',
      'start-date': '30daysAgo',
      'end-date': 'yesterday',
      'sort': '-ga:sessions',
      'max-results': '7'
    },
    chart: {
      type: 'TABLE',
      container: 'os-chart-container',
      options: {
        width: '100%'
      }
    }
  });  

var langChart = new gapi.analytics.googleCharts.DataChart({
    query: {
      'dimensions': 'ga:language',
      'metrics': 'ga:sessions',
      'start-date': '30daysAgo',
      'end-date': 'yesterday',
      'sort': '-ga:sessions',
      'max-results': '7'
    },
    chart: {
	  container: 'lang-chart-container',
      type: 'PIE',
      options: {
        width: '100%',
        pieHole: 4/9
      }
    }
  });

  /**
   * Render the dataChart on the page whenever a new view is selected.
   */
  viewSelector.on('change', function(ids) {
    dataChart.set({query: {ids: ids}}).execute();
    dataChart1.set({query: {ids: ids}}).execute();
    mainChart.set({query: {ids: ids}}).execute();
    refererChart.set({query: {ids: ids}}).execute();
    osChart.set({query: {ids: ids}}).execute();
    langChart.set({query: {ids: ids}}).execute();
  });

});
</script>

<div style="clear:both">
<br>
<h2>IMPORTANT: Makes no use of Javascript or Ajax to keep your website fast and conflicts free</h2>

<div style="background: url(<?php echo plugins_url('../images/techgasp-hr.png', __FILE__); ?>) repeat-x; height: 10px"></div>

<br>

<p>
<a class="button-secondary" href="http://wordpress.techgasp.com" target="_blank" title="Visit Website">More TechGasp Plugins</a>
<a class="button-secondary" href="http://wordpress.techgasp.com/support/" target="_blank" title="Facebook Page">TechGasp Support</a>
<a class="button-primary" href="http://wordpress.techgasp.com/google-analytics-master/" target="_blank" title="Visit Website"><?php echo get_option('google_analytics_master_name'); ?> Info</a>
<a class="button-primary" href="http://wordpress.techgasp.com/google-analytics-master-documentation/" target="_blank" title="Visit Website"><?php echo get_option('google_analytics_master_name'); ?> Documentation</a>
<a class="button-primary" href="http://wordpress.org/plugins/google-analytics-master/" target="_blank" title="Visit Website">RATE US *****</a>
</p>
</div>

<?php
}
}

if( is_multisite() ) {
add_action( 'admin_menu', 'menu_single_google_analytics_admin_statistics_30_days' );
}
else {
add_action( 'admin_menu', 'menu_single_google_analytics_admin_statistics_30_days' );
}
?>
