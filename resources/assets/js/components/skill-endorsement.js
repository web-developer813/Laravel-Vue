var Vue = require('vue');
var VueResource = require('vue-resource');
var _ = require('lodash');
var Sortable = require('sortablejs');
import bootstrap from 'bootstrap';
import helpers from '../mixins/common-helpers.js';

Vue.use(VueResource);

Vue.directive('sortable', {
	inserted: function (el, binding) {
		new Sortable(el, binding.value || {})
	}
})

Vue.component('skill-endorsement', {
	template: '#skill-endorsement-template',

	props: [
		'resourceUrl',
		'currentVolunteerName',
		'loggedAccount',
		'currentVolunteerId',
		'mode'
	],

	mixins: [helpers],

	data: function () {
		return {
			items: [],
			listNameSkills: [],
			loading: true,
			isExpandAllSkills: false,
			skillNameToAdd: '',
			isErrorAddSkill: false,
			listSkillsForModalUpdate: [],
			isShowListEndorsement: false,
			skillSelected: {},
			endorsersForSkillSelected: {},
			existLogginUserModal: false,
			skillsInEndorsing: [],
			isMakingEndorsement: false,
			isGettingListEndorsers: false,
			isCurrentAccount: false,
			loggedAccountData: JSON.parse(this.loggedAccount),
			loggedAccountId: 0
		}
	},

	created: function () {
		this.loggedAccountId = this.loggedAccountData.id
		setTimeout(function() {
			if (this.mode == 'volunteer' && this.loggedAccountId == this.currentVolunteerId)
				this.isCurrentAccount = true
			this.loadItems()
		}.bind(this), 10)
	},

	watch: {
		isExpand: function(val, oldVal) {
			if (val) {
				this.$nextTick(function () {
					this.initTooltip();
				});
			}
		}
	},

	methods: {
		loadItems: function (submit) {
			this.loading = true
			var url = this.resourceUrl
			this.$http.get(url, {}).then(function (response) {
				var data = response.data
				this.items = data.items
				for (var i in this.items) {
					this.listNameSkills.push(this.items[i].name.toLowerCase())
				}
				this.loading = false
				this.$nextTick(function () {
					this.initTooltip();
				});
			}.bind(this));
		},

		loadMore: function () {
			this.loadItems()
		},

		addSkill: function () {
			if (!this.skillNameToAdd || this.loading) return
			if (this.listNameSkills.indexOf(this.skillNameToAdd.toLowerCase()) >= 0) {
				this.isErrorAddSkill = true
				return
			}
			this.isErrorAddSkill = false
			this.loading = true

			var url = this.resourceUrl + '/add'
			this.$http.post(url, { skill: this.skillNameToAdd }).then(function (response) {
				var data = response.data
				if (data.success) {
					this.items.push(data.item)
					this.listNameSkills.push(this.skillNameToAdd.toLowerCase())
				}
				this.skillNameToAdd = ''
				this.loading = false
				document.getElementById("close-modal-add-skill").click()
			}.bind(this));
		},

		onOrderSkill: function (event) {
			var clonedItems = this.listSkillsForModalUpdate.filter(function (item) {
				return item;
			})
			clonedItems.splice(event.newIndex, 0, clonedItems.splice(event.oldIndex, 1)[0])
			this.listSkillsForModalUpdate = []
			this.$nextTick(function () {
				this.listSkillsForModalUpdate = clonedItems
			});
		},

		deleteSkill: function (skill_id) {
			for (var i in this.listSkillsForModalUpdate) {
				if (this.listSkillsForModalUpdate[i].id == skill_id) {
					this.listSkillsForModalUpdate.splice(i, 1);
					break;
				}
			}
		},

		onOpenModalUpdate: function () {
			this.listSkillsForModalUpdate = [];
			for (var i in this.items) {
				this.listSkillsForModalUpdate.push(this.items[i])
			}
		},

		updateSkills: function () {
			var updateData = [];
			for (var i in this.listSkillsForModalUpdate) {
				updateData.push(this.listSkillsForModalUpdate[i].id)
			}

			var url = this.resourceUrl + '/update'
			this.loading = true
			this.$http.post(url, { skills_kept: updateData }).then(function (response) {
				var data = response.data
				this.items = data.items
				for (var i in this.items) {
					this.listNameSkills.push(this.items[i].name.toLowerCase())
				}
				this.loading = false
				document.getElementById("close-modal-edit-skill").click()
			}.bind(this));
		},

		makeEndorsement: function (skill, isModalShow) {
			if (this.isSkillInEndorsing(skill.id)) return

			var arrUrl = this.resourceUrl.split('/')
			var url = this.resourceUrl.replace(arrUrl[arrUrl.length-1], '') + skill.id + '/endorsement'
			this.isMakingEndorsement = true
			this.skillsInEndorsing.push(skill.id)

			this.$http.post(url, {}).then(function (response) {
				var data = response.data
				if (!data.isRemoveEndorsement)
					skill.skill_endorsements.push(data.skillEndorsement)
				else {
					var i;
					for (i in skill.skill_endorsements) {
						if (
							skill.id == data.skillEndorsement.skill_id
							&& skill.skill_endorsements[i].type_endorser == data.skillEndorsement.type_endorser
							&& skill.skill_endorsements[i].endorser_id == data.skillEndorsement.endorser_id
						)
							break
					}
					skill.skill_endorsements.splice(i, 1)
				}
				this.isMakingEndorsement = false
				this.skillsInEndorsing.splice(this.skillsInEndorsing.indexOf(skill.id), 1)
				
				// If in showing endorser list modal
				if (isModalShow) {
					var endorsersCurrentMode = this.endorsersForSkillSelected[this.mode]
					
					if (data.isRemoveEndorsement) {
						var i;
						for (i in endorsersCurrentMode) {
							if (endorsersCurrentMode[i].id == this.loggedAccountId) {
								this.existLogginUserModal = false
								break
							}
						}
						endorsersCurrentMode.splice(i, 1);
					} else {
						endorsersCurrentMode.push(this.loggedAccountData);
					}
				}
			}.bind(this));
		},

		checkUserClicked: function (skill_endorsements) {
			for (var i in skill_endorsements) {
				if (
					skill_endorsements[i].type_endorser == this.mode
					&& skill_endorsements[i].endorser_id == this.loggedAccountId
				)
					return true
			}
			return false
		},

		isSkillInEndorsing: function(skill_id) {
			if (this.skillsInEndorsing.indexOf(skill_id) >= 0) {
				return true
			}
			return false
		},

		showListEndorsement: function (skill) {
			if (skill.skill_endorsements.length == 0 ) return

			this.endorsersForSkillSelected = []
			this.isShowListEndorsement = true
			this.isGettingListEndorsers = true
			this.skillSelected = skill
			
			var arrUrl = this.resourceUrl.split('/')
			var url = this.resourceUrl.replace(arrUrl[arrUrl.length-1], '') + skill.id + '/endorsement/endorsers'

			this.$http.get(url, {}).then(function (response) {
				var data = response.data
				this.endorsersForSkillSelected = {
					'volunteer': data.listVolunteer,
					'nonprofit': data.listNonprofit,
					'forprofit': data.listForprofit
				}

				this.existLogginUserModal = false

				var endorsersCurrentMode = this.endorsersForSkillSelected[this.mode]
				
				for (var i in endorsersCurrentMode) {
					if (endorsersCurrentMode[i].id == this.loggedAccountId) {
						this.existLogginUserModal = true
						break
					}
				}

				this.isGettingListEndorsers = false
			}.bind(this));
		},
	}
})