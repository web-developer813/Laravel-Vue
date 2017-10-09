<div class="skill-endorsement-container">
	<div class="row clearfix">
		<div class="col-md-8">
			<h4 class="header-skill">{{ properize($volunteer->firstname) }} Skills</h4>	
		</div>

		<div class="col-md-4 text-right" v-if="isCurrentAccount">
			<button type="" class="btn btn-dark btn-pure icon md-edit" title="Edit skills" data-toggle="modal" data-target="#modal-edit-skill" v-if="items.length>0" v-on:click="onOpenModalUpdate"></button>
			<button type="" class="btn btn-success btn-pure icon md-plus" title="Add a new skill" data-toggle="modal" data-target="#modal-add-skill"></button>
		</div>
	</div>
	<div v-if="!loading && !items.length">
		<p>{{ $volunteer->firstname }} doesn't have any skills listed just yet! {{ $volunteer->firstname }} may just be acting modest but we're still convinced {{ $volunteer->firstname }} is a skilled individual!</p>
	</div>
	<div v-for="item in items.slice(0, 3)">
		<div class="row clearfix">
			<div class="col-xs-6 col-md-4">
				<span class="more-skill-show">
					<button type="button" class="btn btn-outline-primary" :title="item.name" v-on:click="showListEndorsement(item)">
						@{{item.name}}
						<span class="badge badge-primary badge-pill" v-if="item.skill_endorsements.length>0">@{{item.skill_endorsements.length}}</span>
					</button>
					<span v-if="!checkUserClicked(item.skill_endorsements) && !isSkillInEndorsing(item.id)">
						<button type="button" class="btn btn-pure btn-success icon md-plus-circle tooltip-success tooltip-rotate" v-if="!isCurrentAccount" v-on:click="makeEndorsement(item)" data-toggle="tooltip" data-original-title="Endorse {{ $volunteer->firstname }}" data-placement="right"></button>
					</span>
					<span v-else-if="isSkillInEndorsing(item.id)">
						<button type="button" class="btn btn-pure btn-success icon md-refresh tooltip-success tooltip-rotate" v-if="!isCurrentAccount" data-toggle="tooltip" data-original-title="Endorsing" data-placement="right"></button>
					</span>
					<span v-else>
						<button type="button" class="btn btn-pure btn-success icon md-check-circle tooltip-success tooltip-rotate" v-if="!isCurrentAccount" v-on:click="makeEndorsement(item)" data-toggle="tooltip" data-original-title="You Endorse This" data-placement="right"></button>
					</span>
				</span>
			</div>
			<div class="col-xs-6 col-md-8">
				<span class="skill-endorsement" v-if="item.skill_endorsements.length==0">
					<span v-if="isCurrentAccount">You</span>
					<span v-else>@{{currentVolunteerName}}</span>
					don’t have any endorsements for this skill yet
				</span>
				<span class="skill-endorsement" v-if="item.skill_endorsements.length>0">
					<span v-if="checkUserClicked(item.skill_endorsements)">
						<span v-if="item.skill_endorsements.length==1">
							You have endorsed {{ $volunteer->firstname }} for this skill
						</span>
						<span v-else>
							You and @{{ item.skill_endorsements.length -1 }} others have endorsed {{ $volunteer->firstname }} for this skill
						</span>
					</span>
					<span v-else>
						@{{ item.skill_endorsements.length }} people have endorsed {{ $volunteer->firstname }} for this skill
					</span>
				</span>
			</div>
		</div>
		<hr>
	</div>

	<div v-if="items.length > 3 && isExpandAllSkills">
		<p>{{ $volunteer->firstname }} is also good at…</p>
		<span v-for="item in items.slice(3)" class="more-skill-show">
			<button type="button" :class="!isCurrentAccount? 'btn btn-outline-primary mb-15' : 'btn btn-outline-primary mr-10 mb-15'" :title="item.name" v-on:click="showListEndorsement(item)">
				@{{item.name}}
				<span class="badge badge-primary badge-pill" v-if="item.skill_endorsements.length>0">@{{item.skill_endorsements.length}}</span>
            </button>
            <span v-if="!checkUserClicked(item.skill_endorsements) && !isSkillInEndorsing(item.id)">
				<button type="button" class="btn btn-pure btn-success icon md-plus-circle tooltip-success tooltip-rotate" v-if="!isCurrentAccount" v-on:click="makeEndorsement(item)" data-toggle="tooltip" data-original-title="Endorse {{ $volunteer->firstname }}" data-placement="right"></button>
			</span>
			<span v-else-if="isSkillInEndorsing(item.id)">
				<button type="button" class="btn btn-pure btn-success icon md-refresh tooltip-success tooltip-rotate" v-if="!isCurrentAccount" data-toggle="tooltip" data-original-title="Endorsing" data-placement="right"></button>
			</span>
			<span v-else>
				<button type="button" class="btn btn-pure btn-success icon md-check-circle tooltip-success tooltip-rotate" v-if="!isCurrentAccount" v-on:click="makeEndorsement(item)" data-toggle="tooltip" data-original-title="You Endorse This" data-placement="right"></button>
			</span>
		</span>
	</div>
	<div class="text-center" v-if="items.length > 3">
		<a href="" v-if="!isExpandAllSkills" v-on:click.prevent="isExpandAllSkills=!isExpandAllSkills">
			View @{{items.slice(3).length}} more <i class="md-caret-down"></i>
		</a>
		<a href="" v-if="isExpandAllSkills" v-on:click.prevent="isExpandAllSkills=!isExpandAllSkills">
			Show less <i class="md-caret-up"></i>
		</a>
	</div>
	
	<!-- Modal Add skill -->
	<div class="modal fade" id="modal-add-skill" tabindex="-1" role="dialog" aria-labelledby="addSkillModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" id="close-modal-add-skill" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="addSkillModalLabel">Add Skill</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<input class="form-control" placeholder="Skill (ex: Javascript)" type="text" v-model="skillNameToAdd" v-on:keyup.13="addSkill">
						<p class="text-danger" v-if="isErrorAddSkill">Looks like you already have this skill on your profile.</p>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" v-on:click="addSkill">Add</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal Edit Skill -->
	<div class="modal fade" id="modal-edit-skill" tabindex="-1" role="dialog" aria-labelledby="editSkillModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" id="close-modal-edit-skill" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="editSkillModalLabel">Skills &amp; Endorsements (@{{listSkillsForModalUpdate.length}})</h4>
				</div>
				<div class="modal-body">
					<ul class="list-group" v-sortable="{ onUpdate: onOrderSkill }">
						<li class="list-group-item bg-info item-skill-edit" v-for="item in listSkillsForModalUpdate">
							<a href="" v-on:click.prevent="deleteSkill(item.id)">
								<i class="md-delete"></i>
							</a>
							&nbsp; 
							<span class="name-skill">@{{item.name}}</span>
							<span class="md-menu move-icon"></span>
						</li>
					</ul>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" v-on:click="updateSkills">Save</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal show list endorsement -->
	<modal v-if="isShowListEndorsement" @close="isShowListEndorsement = false">
		<div slot="header">
			<span href="" @click="isShowListEndorsement=false" class="close modal-default-button"><i class="icon md-close"></i></span>
			<h4>@{{skillSelected.name}} <span class="badge badge-pill badge-primary badge-outline">@{{skillSelected.skill_endorsements.length}}</span></h4>	
		</div>
		<div slot="body">
			<div v-for="(value, key) in endorsersForSkillSelected">
				<h5 v-if="value.length>0" class="bg-warning title-mode-popup">
					@{{key}}
				</h5>
				<div class="media" v-for="item in value">
					<img class="d-flex avatar-endorser" :src="item.profile_photo" alt="Avatar">
					<div class="media-body" v-if="key=='volunteer'">
						<h5 class="mt-0">
							<a :href="item.url">@{{item.firstname + ' ' + item.lastname}}</a>
						</h5>
						@{{item.location}}
					</div>
					<div class="media-body" v-if="key=='nonprofit'">
						<h5 class="mt-0">
							<a :href="item.url">@{{item.name}}</a>
						</h5>
						@{{item.full_address}}
					</div>
					<div class="media-body" v-if="key=='forprofit'">
						<h5 class="mt-0">
							<a :href="item.url">@{{item.name}}</a>
						</h5>
						@{{item.full_address}}
					</div>
				</div>
				<br v-if="value.length > 0">
			</div>
		</div>
		<div slot="footer">
			<div v-if="!isGettingListEndorsers && !isMakingEndorsement">
				<button class="btn btn-pure btn-success" @click="makeEndorsement(skillSelected, true)" v-if="!isCurrentAccount && !existLogginUserModal">
					<i class="icon md-plus-circle"></i> Add Endorsement
				</button>
				<button class="btn btn-pure btn-default" @click="makeEndorsement(skillSelected, true)" v-if="!isCurrentAccount && existLogginUserModal">
					<i class="icon md-minus-circle"></i> Remove Endorsement
				</button>
			</div>
			<button class="btn btn-default" disabled v-if="isGettingListEndorsers || isMakingEndorsement"><i class="md-refresh"></i></button>
		</div>
	</modal>
</div>