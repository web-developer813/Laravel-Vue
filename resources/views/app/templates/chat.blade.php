<messenger connections-url="{{ secure_url(URL::route('connections.index','',false)) }}">
<div class="slidePanel slidePanel-right site-sidebar slidePanel-show" v-if="showSidebar">
	<div class="slidePanel-content site-sidebar-content">
		<ul class="site-sidebar-nav nav nav-tabs nav-tabs-line" role="tablist">
		  <li class="nav-item">
		    <a class="nav-link active" data-toggle="tab" href="#sidebar-userlist" role="tab">
		      <i class="icon md-account" aria-hidden="true"></i>
		    </a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link" data-toggle="tab" href="#sidebar-messagelist" role="tab">
		      <i class="icon md-comment" aria-hidden="true"></i>
		    </a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link" data-toggle="tab" href="#sidebar-setting" role="tab">
		      <i class="icon md-settings" aria-hidden="true"></i>
		    </a>
		  </li>
		</ul>

		<div class="site-sidebar-tab-content tab-content">
		  <div class="tab-pane fade active show" id="sidebar-userlist">
		    <div>
		      <div>
		        <h5 class="clearfix">CONNECTIONS <span id="connect-panel" class="connect-panel">Status</span>
		          <div class="float-right">
		            <a class="text-action" href="javascript:void(0)" role="button">
		              <i class="icon md-plus" aria-hidden="true"></i>
		            </a>
		            <a class="text-action" href="javascript:void(0)" role="button">
		              <i class="icon md-more" aria-hidden="true"></i>
		            </a>
		          </div>
		        </h5>
		        <form class="my-20" role="search">
		          <div class="input-search input-search-dark">
		            <i class="input-search-icon md-search" aria-hidden="true"></i>
		            <input type="text" class="form-control" id="inputSearch" name="search" placeholder="Search Connections">
		            <button type="button" class="input-search-close icon md-close" aria-label="Close"></button>
		          </div>
		        </form>
		        <div class="list-group">
		          <div class="row" id="status-row"></div>
		          	<a class="list-group-item" href="javascript:void(0)" data-toggle="show-chat" v-for="item in items">
		              <div class="media">
		                <div class="pr-20">
		                  <div :class="item.isOnline ? 'avatar avatar-sm avatar-online' : 'avatar avatar-sm avatar-off'">
		                    <img :src="item.profile_photo" :alt="item.name" />
		                    <i></i>
		                  </div>
		                </div>
		                <div class="media-body">
		                  <h5 class="mt-0 mb-5">@{{ item.name }}</h5>
		                  <small>@{{ item.location }}</small>
		                </div>
		              </div>
		            </a>
	
		          <a class="list-group-item" href="javascript:void(0)" v-if="!loading && !items.length">You currently don't have any connections. Why not get started now?</a>

		        </div>
		      </div>
		    </div>
		  </div>
		  <div class="tab-pane fade" id="sidebar-messagelist">
		    <div id="listmessage">Messages</div>
		  </div>
		  <div class="tab-pane fade" id="sidebar-setting">
		    Settings to go here
		  </div>
		</div>

		<div id="conversation" class="conversation">
		  <div class="conversation-header">
		    <a class="conversation-more float-right" href="javascript:void(0)">
		      <i class="icon md-more" aria-hidden="true"></i>
		    </a>
		    <a class="conversation-return float-left" href="javascript:void(0)" data-toggle="close-chat">
		      <i class="icon md-chevron-left" aria-hidden="true"></i>
		    </a>
		    <div class="conversation-title">Mike</div>
		  </div>
		  <div id="message-list" class="chats">
		    <div class="chat chat-left">
		      <div class="chat-avatar">
		        <a class="avatar" data-toggle="tooltip" href="#" data-placement="left" title="Edward Fletcher">
		          <img src="https://randomuser.me/api/portraits/men/5.jpg" alt="Edward Fletcher">
		        </a>
		      </div>
		      <div class="chat-body">
		        <div class="chat-content">
		          <p>
		            I'm just looking around.
		          </p>
		          <p>Will you tell me something about yourself? </p>
		          <time class="chat-time" datetime="2015-06-01T08:35">8:35 am</time>
		        </div>
		        <div class="chat-content">
		          <p>
		            Are you there? That time!
		          </p>
		          <time class="chat-time" datetime="2015-06-01T08:40">8:40 am</time>
		        </div>
		      </div>
		    </div>
		    <div class="chat">
		      <div class="chat-avatar">
		        <a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title="June Lane">
		          <img src="https://randomuser.me/api/portraits/men/4.jpg" alt="June Lane">
		        </a>
		      </div>
		      <div class="chat-body">
		        <div class="chat-content">
		          <p>
		            Hello. What can I do for you?
		          </p>
		          <time class="chat-time" datetime="2015-06-01T08:30">8:30 am</time>
		        </div>
		      </div>
		    </div>
		  </div>
		  <div class="row" id="typing-row"></div>
		  <div class="conversation-reply">
		    <div class="input-group">
		      <span class="input-group-btn">
		        <a href="javascript: void(0)" class="btn btn-pure btn-default icon md-plus"></a>
		      </span>
		      <input class="form-control" type="text" placeholder="Say something">
		      <span class="input-group-btn">
		        <a href="javascript: void(0)" class="btn btn-pure btn-default icon md-image"></a>
		      </span>
		    </div>
		  </div>
		</div>
	</div>
	<div class="slidePanel-handler"></div>
	<div class="slidePanel-loading slidePanel-loading-show">
		<div class="loader loader-default"></div>
	</div>
</div>
</messenger>