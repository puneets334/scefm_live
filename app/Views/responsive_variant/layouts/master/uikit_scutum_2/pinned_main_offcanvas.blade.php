<div class="bg-dark text-white uk-text-center" style="height:330px;" uk-margin>
    <div class="uk-label uk-text-lead text-white uk-margin-medium-bottom" style="padding:0.3rem 1.3rem;font-weight:500;">
        <i class="mdi mdi-cloud-tags"></i>&nbsp;&nbsp;
        SC-eFM
    </div>

    <div>
        <img class="uk-border-circle" src="{{base_url('assets/responsive_variant/images/avatars/user-male-icon.png')}}" width="100" height="100" alt="Person Photo">
    </div>
    <!--todo:appli trailing... on limit exceed-->
    @if(!empty($_SESSION['login']['impersonator_user']->id))
    <div>
        <span class="uk-label uk-label-success" uk-tooltip="eFiling Assistant working on behalf of {{trim(ucwords(strtolower($_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'])))}}">EA - {{trim(strtolower($_SESSION['login']['impersonator_user']->first_name . ' ' . $_SESSION['login']['impersonator_user']->last_name))}}</span>
    </div>
    <div class="uk-text-bold uk-text-capitalize">{{trim(strtolower($_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name']))}}</div>
    @endif
    <div class="uk-article-meta">Advocate on Record (Code - 1024)</div>
    <div class="uk-child-width-1-3" uk-grid>
        <div>
            <a href="{{base_url('my/profile')}}" uk-tooltip="Profile" class="uk-icon-button sc-icon-24" style="width:42px;height:42px"><i class="mdi mdi-account-edit"></i></a>
        </div>
        <div>
            <a href="#" uk-tooltip="Cases" class="uk-icon-button sc-icon-24" style="width:42px;height:42px"><i class="mdi mdi-file-cabinet"></i></a>
        </div>
        <div>
            <a href="#" uk-tooltip="Settings" class="uk-icon-button sc-icon-24" style="width:42px;height:42px"><i class="mdi mdi-settings"></i></a>
        </div>
    </div>
</div>
<div class="uk-background-default ukpadding-small uk-child-width-1-2 uk-grid-small" uk-grid>
    <a href="{{base_url('dashboard')}}" style="text-decoration:none;">
        <div class="{{current_url() == base_url('dashboard') ? 'bg-dark text-white' : ''}} md-color-grey-600 uk-border-rounded uk-card-hover uk-text-center uk-padding-small">
            <div class="uk-card-title">
                <i class="mdi mdi-home sc-icon-36"></i>
            </div>
            <span>Home</span>
        </div>
    </a>
    <a href="{{base_url('cases')}}" style="text-decoration:none;">
        <div class="{{current_url() == base_url('cases') ? 'bg-dark text-white' : ''}} md-color-grey-600 uk-border-rounded uk-card-hover uk-text-center uk-padding-small">
            <div class="uk-card-title">
                <i class="mdi mdi-file-cabinet sc-icon-36"></i>
            </div>
            <span>Cases</span>
        </div>
    </a>
    <a href="{{base_url('causelist')}}" style="text-decoration:none;">
        <div class="{{current_url() == base_url('causelist') ? 'bg-dark text-white' : ''}} md-color-grey-600 uk-border-rounded uk-card-hover uk-text-center uk-padding-small">
            <div class="uk-card-title">
                <i class="mdi mdi-format-list-numbered sc-icon-36"></i>
            </div>
            <span>Cause-List</span>
        </div>
    </a>
    <a href="https://efiling.ecourts.gov.in/assets/downloads/efiling-User-manual.pdf" target="_blank" style="text-decoration:none;">
        <div class="md-color-grey-600 uk-border-rounded uk-card-hover uk-text-center uk-padding-small">
            <div class="uk-card-title">
                <i class="mdi mdi-book-open-outline sc-icon-36"></i>
            </div>
            <span>Guides</span>
        </div>
    </a>
    <a href="{{base_url('support')}}" style="text-decoration:none;">
        <div class="{{current_url() == base_url('support') ? 'bg-dark text-white' : ''}} md-color-grey-600 uk-border-rounded uk-card-hover uk-text-center uk-padding-small">
            <div class="uk-card-title">
                <i class="mdi mdi-help-circle-outline sc-icon-36"></i>
            </div>
            <span>Assistance</span>
        </div>
    </a>
</div>
