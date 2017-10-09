@extends('volunteers.layout')

@section('page_id', 'messages')

@section('body_class', 'animsition app-message page-aside-scroll page-aside-left')

@section('page-content')
<threads resource-url="{{ secure_url(URL::route('threads.list','',false)) }}" inline-template>
<div class="page">
	<div class="page-aside">
		<div class="page-aside-switch">
			<i class="icon md-chevron-left" aria-hidden="true"></i>
			<i class="icon md-chevron-right" aria-hidden="true"></i>
		</div>
		<div class="page-aside-inner">
			<div class="header-section thread-header">
				<h5 class="pl-10">Messages</h5>
				<div class="header-actions">
					<button type="button" class="btn btn-pure btn-primary icon md-edit" @click="startThread"></button>
				</div>
			</div>
			<div class="input-search">
				<button class="input-search-btn" type="submit">
					<i class="icon md-search" aria-hidden="true"></i>
				</button>
				<form>
					<input class="form-control" type="text" placeholder="Search Threads" name="">
				</form>
			</div>
			<div class="app-message-list page-aside-scroll">
				<div data-role="container">
					<div data-role="content">
						<ul class="list-group">
							<template v-for="(item, index) in items">
							<li :class="listClasses(item)" @click="activate(item.id,index)">
								<div class="media">
									<div class="pr-20" v-if="item.new">
										<template v-for="(avatar,index) in newMessageAvatar">
											<a :class="index > 0 ? 'avatar avatar-behind' + ' avatar-behind-' + index : 'avatar avatar-front'" href="javascript:void(0)">
												<img class="img-fluid" :src="avatar.profile_photo" alt="new">
											</a>
										</template>
									</div>
									<div class="pr-20" v-else>
										<span v-if="item.creator.id === {{ $authVolunteer->id }}">
											<template v-for="(avatar,index1) in item.recipients">
												<a :class="avatarClasses(avatar,index1)"  href="javascript:void(0)" v-if="index1 < 3">
													<img class="img-fluid" :src="avatar.profile_photo" :alt="avatar.name">
													<i v-if="index1 == 0"></i>
												</a>
											</template>
										</span>
										<span v-else>
											<a :class="item.creator.status === 1 ? 'avatar avatar-front avatar-online avatar-' + item.creator.id : 'avatar avatar-front avatar-off avatar-' + item.creator.id">
												<img class="img-fluid" :src="item.creator.profile_photo" alt="item.creator.name">
												<i></i>
											</a>
											<template v-for="(avatar,index2) in item.recipients" v-if="item.recipients.length > 1">
												<a :class="'avatar avatar-behind avatar-behind-' + (index2+1)" href="javascript:void(0)" v-if="index2 < 2">
													<img class="img-fluid" :src="avatar.profile_photo" :alt="avatar.name">
												</a>
											</template>
										</span>

									</div>
									<div class="media-body">
										<h5 class="mt-0 mb-5">@{{ item.new ? newMessageTitle : item.title }}</h5>
										<span class="font-size-10">@{{ item.new ? newMessageExcerpt : item.excerpt }}</span>
									</div>
									<div class="pl-20">
										<small class="media-time">@{{ item.updated_at | timeago }}</small>
										{{--<span class="badge badge-pill up badge-success" v-if="item.unread_messages.length >= 1">@{{ item.unread_messages }}</span>--}}
										<i class="icon md-circle blue-500" v-if="item.unread_messages"></i>
									</div>
								</div>
							</li>
							</template>
						</ul>
						<p class="mt-20 pl-20 pr-20" v-show="!loading && !items.length">Your inbox is empty...time to go out and volunteer!</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<messages resource-url="{{ secure_url(URL::route('messages.list','',false)) }}" :title.sync="activeThreadTitle" :to.sync="activeThreadRecipients" :new-thread.sync="newThread" :active="active" @createthread="startThread" @useradded="addtothread" @userremoved="removefromthread" @addmessage="addNewMessage" inline-template>
		<div class="page-main">
			<div class="header-section message-header" v-show="!loading && items.length">
		    	<h5 class="text-center pl-20 pr-20">Conversation With: @{{ title }}</h5>
			</div>
			<div class="app-message-chats" v-show="!loading && items.length" v-chat-scroll="{always: false}">
		    	<button type="button" id="historyBtn" class="btn btn-round btn-default btn-flat primary-500" v-if="initialTotal > 20">View History</button>
		    	<template v-for="item in items">
		    	<div class="chats">
		    		<div :class="item.mine ? 'chat' : 'chat chat-left'">
		    			<div class="chat-avatar">
		    				<a class="avatar" data-toggle="tooltip" href="#" :data-placement="item.mine ? 'right' : 'left'" :title="item.actor.name">
		    					<img :src="item.actor.profile_photo" :alt="item.actor.name">
		    				</a>
		    			</div>
		    			<div class="chat-body">
		    				<div class="chat-content">
		    					<p>@{{ item.body }}</p>
		    				</div>
		    			</div>
		    		</div>
		    	</div>
		    	</template>
		    	<div class="chats" v-show="isTyping">
		    		<div class="chat chat-left">
		    			<div class="chat-body">
		    				<div class="chat-content">
		    					<div class="pl-10 pr-10"><div class="loader vertical-align-middle loader-ellipsis-white font-size-14"></div></div>
		    				</div>
		    			</div>
		    			<div class="clearfix text-left"><small>@{{ typer }} is typing...</small></div>
		    		</div>
		    	</div>
		    </div>
		    <div class="header-section message-header" v-show="!loading && !items.length && newThread">
		    	<label class="pr-20">To:</label>
		    	<connections-selector resource-url="{{ secure_url(URL::route('connections.index','',false)) }}" @adduser="useradded" @removeuser="userremoved"></connections-selector>
			</div>
		    <div class="app-message-chats" id="new-message" v-show="!loading && !items.length && newThread">
			    <div class="vertical-align">
			    	<div class="vertical-align-middle">
				    	
				    </div>
				</div>
			</div>
		    <div class="app-message-chats vertical-align" v-if="!loading && !items.length && !newThread">
	    		<div class="vertical-align-middle">
	    			<h4>No message selected</h4>
	    			<p>Select one from the list on the left or create a new one</p>
	    			<button class="btn btn-default" @click="createthread">
	    				<i class="icon md-edit"></i> Create A New Message
	    			</button>
	    		</div>
	    	</div>
	    	<div class="app-message-chats vertical-align" v-show="loading">
	    		<div class="loader vertical-align-middle loader-default"></div>
	    	</div>
		    <!-- End Chat Box -->
		    <!-- Message Input-->
		    <form class="app-message-input" v-on:submit.prevent @keyup.enter="addmessage" v-show="!loading && items.length || !loading && newThread">
		    	<div class="input-group form-material">
		    		<span class="input-group-btn">
		    			<a href="javascript: void(0)" class="btn btn-pure btn-default icon md-videocam"></a>
		    		</span>
		    		<input class="form-control" type="text" placeholder="Type message here ..." v-model="messageText">
		    		<span class="input-group-btn">
		    			<button type="submit" class="btn btn-pure btn-default icon md-mail-send"></button>
		    		</span>
		    	</div>
		    </form>
		</div>
	</messages>
</div>
</threads>
@stop