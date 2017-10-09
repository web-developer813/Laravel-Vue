<div class="skill-oppotunity-container">
	<div class="row clearfix">
		<div class="col-md-8">
			<h4 class="header-skill">Skills</h4>	
		</div>
		<div class="col-md-4 text-right" v-if="isCurrentAccount">
			<button type="" class="btn btn-primary" title="Add a new skill" data-toggle="modal" data-target="#modal-add-skill">
				<i class="md-plus"></i>
			</button>
			<button type="" class="btn btn-info" title="Edit skill" data-toggle="modal" data-target="#modal-edit-skill" v-if="items.length>0" v-on:click="onOpenModalUpdate">
				<i class="md-edit"></i>
			</button>
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-4 col-sm-6" v-for="item in items">
			<span 
				class="skill-name" 
				:title="item.name"
				v-on:click="showListEndorsement(item)">
				@{{item.name}}
			</span>
		</div>
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
					<h4 class="modal-title" id="editSkillModalLabel">Skills & Endorsements (@{{listSkillsForModalUpdate.length}})</h4>
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
</div>