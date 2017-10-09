<transition name="animation-slide-right">
  <div class="slidePanel slidePanel-right site-sidebar slidePanel-show" v-show="showSidebar">
    <threads resource-url="{{ secure_url(URL::route('threads.list','',false)) }}" inline-template>
      <div class="slidePanel-content site-sidebar-content">
        <h5 class="pt-30 pr-20 pb-30 pl-20 mt-0 mr-0 mb-0 ml-0 bg-grey-50">NEW MESSAGES
          <div class="float-right">
            <a class="text-action" href="{{ secure_url(URL::route('app.messages.list')) }}" role="button" data-toggle="tooltip" data-original-title="View All Messages" data-placement="left">
              <i class="icon md-comment-more" aria-hidden="true"></i>
            </a>
          </div>
        </h5> 
        <hr class="mt-0">           
        <div class="list-group">
          <template v-for="(item, index) in items">
          <a class="list-group-item" @click="activate(item.id,index)">
            <div class="media">
              <div class="pr-10">
                <p class="blue-500">
                  <i class="icon md-circle"></i>
                </p>
              {{--
                <span v-if="item.creator.id === {{ $authVolunteer->id }}">
                  <template v-for="(avatar,index1) in item.recipients">
                    <a :class="avatarClasses(avatar,index1)"  href="javascript:void(0)" v-if="index1 < 3">
                      <img class="img-fluid" :src="avatar.profile_photo" :alt="avatar.name">
                      <i v-if="index1 == 0"></i>
                    </a>
                  </template>
                </span>
                <span v-else>
                  <div :class="item.creator.status === 1 ? 'avatar avatar-front avatar-online avatar-' + item.creator.id : 'avatar avatar-front avatar-off avatar-' + item.creator.id">
                    <img class="img-fluid" :src="item.creator.profile_photo" alt="item.creator.name">
                    <i></i>
                  </div>
                  <template v-for="(avatar,index2) in item.recipients" v-if="item.recipients.length > 1">
                    <div :class="'avatar avatar-behind avatar-behind-' + (index2+1)" href="javascript:void(0)" v-if="index2 < 2">
                      <img class="img-fluid" :src="avatar.profile_photo" :alt="avatar.name">
                    </div>
                  </template>
                </span>
                --}}
              </div>
              <div class="media-body">
                <h5 class="mt-0 mb-5">@{{ item.title }}</h5>
                <p class="font-size-10 mb-0">@{{ item.updated_at | timeago }}</p>
              </div>
            </div>
          </a>
          </template>
        </div>
        <div class="mb-20 pr-20 pl-20 text-center">
          <a href="{{ secure_url(URL::route('app.messages.list')) }}" class="green-500" role="button">
            <i class="icon md-comment-more"></i> View All Of Your Messages
          </a>
        </div>
        <p class="pt-20 pb-20 pl-20 pr-20" v-show="!loading && !items.length">Your inbox is empty...time to go out and volunteer!</p>
        <hr class="mb-0">
        <connections-online resource-url="{{ secure_url(URL::route('connections.index','',false)) }}" inline-template>
          <div>
            <h5 class="pt-30 pr-20 pb-30 pl-20 mt-0 mr-0 mb-0 ml-0 bg-grey-50">ONLINE CONNECTIONS
              {{--
              <div class="float-right">
                <a class="text-action" href="javascript:void(0)" role="button">
                  <i class="icon md-plus" aria-hidden="true"></i>
                </a>
                <a class="text-action" href="javascript:void(0)" role="button">
                  <i class="icon md-more" aria-hidden="true"></i>
                </a>
              </div> --}}
            </h5>
            <hr class="mt-0">
            {{--
            <form class="my-20" role="search">
              <div class="input-search input-search-dark">
                <i class="input-search-icon md-search" aria-hidden="true"></i>
                <input type="text" class="form-control" name="search" placeholder="Search Connections" v-model="search">
                <button type="button" class="input-search-close icon md-close" aria-label="Close"></button>
              </div>
            </form> --}}
            <div class="list-group">
              <template v-for="item in filteredItems">
              <a class="list-group-item" href="javascript:void(0)" @click="getConversation(item.id)">
                <div class="media">
                  <div class="pr-20">
                    <div class="avatar avatar-sm avatar-online">
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
              </template>
              <a class="list-group-item" href="javascript:void(0)" v-if="!loading && !filteredItems.length">None of your connections are online currently.</a>
              <div class="slidePanel-loading" v-if="loading">
                <div class="loader loader-default"></div>
              </div>
            </div>
            <div id="new-conversation" :class="showConversation ? 'conversation active' : 'conversation'" v-show="showConversation">
              <div id="conversation-header" class="conversation-header">
                <a class="conversation-more float-right" href="javascript:void(0)">
                  <i class="icon md-more" aria-hidden="true"></i>
                </a>
                <a class="conversation-return float-left" href="javascript:void(0)" @click="showConversation = false">
                  <i class="icon md-chevron-left" aria-hidden="true"></i>
                </a>
                <div id="chat-title" class="conversation-title"></div>
              </div>
              <div id="message-list" class="chats">
              </div>
              <div id="typing-placeholder" class="chats"></div>
              <div id="typing-row"></div>
              <div class="conversation-reply">
                <div class="input-group">
                  <span class="input-group-btn">
                    <a href="javascript: void(0)" class="btn btn-pure btn-default icon md-plus"></a>
                  </span>
                  <input id="chat-message-input" class="form-control" type="text" placeholder="Say something">
                  <span class="input-group-btn">
                    <a href="javascript: void(0)" class="btn btn-pure btn-default icon md-image"></a>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </connections-online>
        <div id="existing-conversation" class="conversation">
          <div id="conversation-header" class="conversation-header">
            <a class="conversation-more float-right" href="javascript:void(0)">
              <i class="icon md-more" aria-hidden="true"></i>
            </a>
            <a class="conversation-return float-left" href="javascript:void(0)" data-toggle="close-chat">
              <i class="icon md-chevron-left" aria-hidden="true"></i>
            </a>
            <div id="chat-title" class="conversation-title"></div>
          </div>
          <div id="message-list" class="chats">
          </div>
          <div id="typing-placeholder" class="chats"></div>
          <div id="typing-row"></div>
          <div class="conversation-reply">
            <div class="input-group">
              <span class="input-group-btn">
                <a href="javascript: void(0)" class="btn btn-pure btn-default icon md-plus"></a>
              </span>
              <input id="chat-message-input" class="form-control" type="text" placeholder="Say something">
              <span class="input-group-btn">
                <a href="javascript: void(0)" class="btn btn-pure btn-default icon md-image"></a>
              </span>
            </div>
          </div>
        </div>
      </div>
    </threads>
    <div class="slidePanel-handler"></div>
    {{--
    <div class="slidePanel-loading" v-if="loading">
      <div class="loader loader-default"></div>
    </div> --}}
  </div>
</transition>
