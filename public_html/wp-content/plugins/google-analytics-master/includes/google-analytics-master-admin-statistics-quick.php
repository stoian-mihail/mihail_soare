<?php
function menu_single_google_analytics_admin_statistics_quick(){
	if ( is_admin() )
	add_submenu_page( 'google-analytics-master', 'Statistics Quick', 'Statistics Quick', 'manage_options', 'google-analytics-master-admin-statistics-quick', 'google_analytics_master_admin_statistics_quick' );
}

function google_analytics_master_admin_statistics_quick(){
?>
<div class="wrap">
<h2>Statistics Quick</h2>
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
    <div id="view-selector-container"></div>
  </header>

  <ul class="FlexGrid FlexGrid--halves">
    <li class="FlexGrid-item">
      <div class="Chartjs">
        <header class="Titles">
          <h1 class="Titles-main">This Week vs Last Week</h1>
          <div class="Titles-sub">By sessions</div>
        </header>
        <figure class="Chartjs-figure" id="chart-1-container"></figure>
        <ol class="Chartjs-legend" id="legend-1-container"></ol>
      </div>
    </li>
    <li class="FlexGrid-item">
      <div class="Chartjs">
        <header class="Titles">
          <h1 class="Titles-main">This Year vs Last Year</h1>
          <div class="Titles-sub">By users</div>
        </header>
        <figure class="Chartjs-figure" id="chart-2-container"></figure>
        <ol class="Chartjs-legend" id="legend-2-container"></ol>
      </div>
    </li>
    <li class="FlexGrid-item">
      <div class="Chartjs">
        <header class="Titles">
          <h1 class="Titles-main">Top Browsers</h1>
          <div class="Titles-sub">By pageview</div>
        </header>
        <figure class="Chartjs-figure" id="chart-3-container"></figure>
        <ol class="Chartjs-legend" id="legend-3-container"></ol>
      </div>
    </li>
    <li class="FlexGrid-item">
      <div class="Chartjs">
        <header class="Titles">
          <h1 class="Titles-main">Top Countries</h1>
          <div class="Titles-sub">By sessions</div>
        </header>
        <figure class="Chartjs-figure" id="chart-4-container"></figure>
        <ol class="Chartjs-legend" id="legend-4-container"></ol>
      </div>
    </li>
  </ul>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>

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
   * Create a new ActiveUsers instance to be rendered inside of an
   * element with the id "active-users-container" and poll for changes every
   * five seconds.
   */
  var activeUsers = new gapi.analytics.ext.ActiveUsers({
    container: 'active-users-container',
    pollingInterval: 5
  });


  /**
   * Add CSS animation to visually show the when users come and go.
   */
  activeUsers.once('success', function() {
    var element = this.container.firstChild;
    var timeout;

    this.on('change', function(data) {
      var element = this.container.firstChild;
      var animationClass = data.delta > 0 ? 'is-increasing' : 'is-decreasing';
      element.className += (' ' + animationClass);

      clearTimeout(timeout);
      timeout = setTimeout(function() {
        element.className =
            element.className.replace(/ is-(increasing|decreasing)/g, '');
      }, 3000);
    });
  });


  /**
   * Create a new ViewSelector2 instance to be rendered inside of an
   * element with the id "view-selector-container".
   */
  var viewSelector = new gapi.analytics.ext.ViewSelector2({
    container: 'view-selector-container',
  })
  .execute();


  /**
   * Update the activeUsers component, the Chartjs charts, and the dashboard
   * title whenever the user changes the view.
   */
  viewSelector.on('viewChange', function(data) {
    var title = document.getElementById('view-name');
    title.innerHTML = data.property.name + ' (' + data.view.name + ')';

    // Start tracking active users for this view.
    activeUsers.set(data).execute();

    // Render all the of charts for this view.
    renderWeekOverWeekChart(data.ids);
    renderYearOverYearChart(data.ids);
    renderTopBrowsersChart(data.ids);
    renderTopCountriesChart(data.ids);
  });


  /**
   * Draw the a chart.js line chart with data from the specified view that
   * overlays session data for the current week over session data for the
   * previous week.
   */
  function renderWeekOverWeekChart(ids) {

    // Adjust `now` to experiment with different days, for testing only...
    var now = moment(); // .subtract(3, 'day');

    var thisWeek = query({
      'ids': ids,
      'dimensions': 'ga:date,ga:nthDay',
      'metrics': 'ga:sessions',
      'start-date': moment(now).subtract(1, 'day').day(0).format('YYYY-MM-DD'),
      'end-date': moment(now).format('YYYY-MM-DD')
    });

    var lastWeek = query({
      'ids': ids,
      'dimensions': 'ga:date,ga:nthDay',
      'metrics': 'ga:sessions',
      'start-date': moment(now).subtract(1, 'day').day(0).subtract(1, 'week')
          .format('YYYY-MM-DD'),
      'end-date': moment(now).subtract(1, 'day').day(6).subtract(1, 'week')
          .format('YYYY-MM-DD')
    });

    Promise.all([thisWeek, lastWeek]).then(function(results) {

      var data1 = results[0].rows.map(function(row) { return +row[2]; });
      var data2 = results[1].rows.map(function(row) { return +row[2]; });
      var labels = results[1].rows.map(function(row) { return +row[0]; });

      labels = labels.map(function(label) {
        return moment(label, 'YYYYMMDD').format('ddd');
      });

      var data = {
        labels : labels,
        datasets : [
          {
            label: 'Last Week',
            fillColor : 'rgba(220,220,220,0.5)',
            strokeColor : 'rgba(220,220,220,1)',
            pointColor : 'rgba(220,220,220,1)',
            pointStrokeColor : '#fff',
            data : data2
          },
          {
            label: 'This Week',
            fillColor : 'rgba(151,187,205,0.5)',
            strokeColor : 'rgba(151,187,205,1)',
            pointColor : 'rgba(151,187,205,1)',
            pointStrokeColor : '#fff',
            data : data1
          }
        ]
      };

      new Chart(makeCanvas('chart-1-container')).Line(data);
      generateLegend('legend-1-container', data.datasets);
    });
  }


  /**
   * Draw the a chart.js bar chart with data from the specified view that
   * overlays session data for the current year over session data for the
   * previous year, grouped by month.
   */
  function renderYearOverYearChart(ids) {

    // Adjust `now` to experiment with different days, for testing only...
    var now = moment(); // .subtract(3, 'day');

    var thisYear = query({
      'ids': ids,
      'dimensions': 'ga:month,ga:nthMonth',
      'metrics': 'ga:users',
      'start-date': moment(now).date(1).month(0).format('YYYY-MM-DD'),
      'end-date': moment(now).format('YYYY-MM-DD')
    });

    var lastYear = query({
      'ids': ids,
      'dimensions': 'ga:month,ga:nthMonth',
      'metrics': 'ga:users',
      'start-date': moment(now).subtract(1, 'year').date(1).month(0)
          .format('YYYY-MM-DD'),
      'end-date': moment(now).date(1).month(0).subtract(1, 'day')
          .format('YYYY-MM-DD')
    });

    Promise.all([thisYear, lastYear]).then(function(results) {
      var data1 = results[0].rows.map(function(row) { return +row[2]; });
      var data2 = results[1].rows.map(function(row) { return +row[2]; });
      var labels = ['Jan','Feb','Mar','Apr','May','Jun',
                    'Jul','Aug','Sep','Oct','Nov','Dec'];

      // Ensure the data arrays are at least as long as the labels array.
      // Chart.js bar charts don't (yet) accept sparse datasets.
      for (var i = 0, len = labels.length; i < len; i++) {
        if (data1[i] === undefined) data1[i] = null;
        if (data2[i] === undefined) data2[i] = null;
      }

      var data = {
        labels : labels,
        datasets : [
          {
            label: 'Last Year',
            fillColor : 'rgba(220,220,220,0.5)',
            strokeColor : 'rgba(220,220,220,1)',
            data : data2
          },
          {
            label: 'This Year',
            fillColor : 'rgba(151,187,205,0.5)',
            strokeColor : 'rgba(151,187,205,1)',
            data : data1
          }
        ]
      };

      new Chart(makeCanvas('chart-2-container')).Bar(data);
      generateLegend('legend-2-container', data.datasets);
    })
    .catch(function(err) {
      console.error(err.stack);
    });
  }


  /**
   * Draw the a chart.js doughnut chart with data from the specified view that
   * show the top 5 browsers over the past seven days.
   */
  function renderTopBrowsersChart(ids) {

    query({
      'ids': ids,
      'dimensions': 'ga:browser',
      'metrics': 'ga:pageviews',
      'sort': '-ga:pageviews',
      'max-results': 5
    })
    .then(function(response) {

      var data = [];
      var colors = ['#4D5360','#949FB1','#D4CCC5','#E2EAE9','#F7464A'];

      response.rows.forEach(function(row, i) {
        data.push({ value: +row[1], color: colors[i], label: row[0] });
      });

      new Chart(makeCanvas('chart-3-container')).Doughnut(data);
      generateLegend('legend-3-container', data);
    });
  }


  /**
   * Draw the a chart.js doughnut chart with data from the specified view that
   * compares sessions from mobile, desktop, and tablet over the past seven
   * days.
   */
  function renderTopCountriesChart(ids) {
    query({
      'ids': ids,
      'dimensions': 'ga:country',
      'metrics': 'ga:sessions',
      'sort': '-ga:sessions',
      'max-results': 5
    })
    .then(function(response) {

      var data = [];
      var colors = ['#4D5360','#949FB1','#D4CCC5','#E2EAE9','#F7464A'];

      response.rows.forEach(function(row, i) {
        data.push({
          label: row[0],
          value: +row[1],
          color: colors[i]
        });
      });

      new Chart(makeCanvas('chart-4-container')).Doughnut(data);
      generateLegend('legend-4-container', data);
    });
  }


  /**
   * Extend the Embed APIs `gapi.analytics.report.Data` component to
   * return a promise the is fulfilled with the value returned by the API.
   * @param {Object} params The request parameters.
   * @return {Promise} A promise.
   */
  function query(params) {
    return new Promise(function(resolve, reject) {
      var data = new gapi.analytics.report.Data({query: params});
      data.once('success', function(response) { resolve(response); })
          .once('error', function(response) { reject(response); })
          .execute();
    });
  }


  /**
   * Create a new canvas inside the specified element. Set it to be the width
   * and height of its container.
   * @param {string} id The id attribute of the element to host the canvas.
   * @return {RenderingContext} The 2D canvas context.
   */
  function makeCanvas(id) {
    var container = document.getElementById(id);
    var canvas = document.createElement('canvas');
    var ctx = canvas.getContext('2d');

    container.innerHTML = '';
    canvas.width = container.offsetWidth;
    canvas.height = container.offsetHeight;
    container.appendChild(canvas);

    return ctx;
  }


  /**
   * Create a visual legend inside the specified element based off of a
   * Chart.js dataset.
   * @param {string} id The id attribute of the element to host the legend.
   * @param {Array.<Object>} items A list of labels and colors for the legend.
   */
  function generateLegend(id, items) {
    var legend = document.getElementById(id);
    legend.innerHTML = items.map(function(item) {
      var color = item.color || item.fillColor;
      var label = item.label;
      return '<li><i style="background:' + color + '"></i>' + label + '</li>';
    }).join('');
  }


  // Set some global Chart.js defaults.
  Chart.defaults.global.animationSteps = 60;
  Chart.defaults.global.animationEasing = 'easeInOutQuart';
  Chart.defaults.global.responsive = true;
  Chart.defaults.global.maintainAspectRatio = false;

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
add_action( 'admin_menu', 'menu_single_google_analytics_admin_statistics_quick' );
}
else {
add_action( 'admin_menu', 'menu_single_google_analytics_admin_statistics_quick' );
}
?>
