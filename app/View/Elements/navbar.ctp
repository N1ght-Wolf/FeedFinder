    <md-toolbar ng-controller="navbarController">
      <div class="md-toolbar-tools">
        <h2>
          <span>Feed-Finder</span>
        </h2>
        <span flex></span>
        <md-button aria-label="Learn More" href="/feedfinder/dashboards">
          Dashboard
          <md-tooltip md-visible="demo.showTooltip" md-direction="{{demo.tipDirection}}">
        Go to dashboard
      </md-tooltip>
        </md-button>
        <md-button ng-click="showAlert($event)" aria-label="Learn More">
          Contact
          <md-tooltip md-visible="demo.showTooltip"  md-direction="{{demo.tipDirection}}">
        Contact Us!
      </md-tooltip>
        </md-button>
      </div>
    </md-toolbar>

    