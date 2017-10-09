var Vue = require('vue');
var VueResource = require('vue-resource');
var _ = require('lodash');
var Sortable = require('sortablejs');

Vue.use(VueResource);

Vue.directive('sortable', {
	inserted: function (el, binding) {
		new Sortable(el, binding.value || {})
	}
})

Vue.component('skill-opportunity', {
	template: '#skill-opportunity-template',

	props: [
		'resourceUrl',
		'opportunityTitle',
		'loggedAccount',
		'opportunityId',
		'mode'
	],

	data: function () {
		return {
			items: [],
			listNameSkills: [],
			loading: true,
			isExpandAllSkills: false,
			skillNameToAdd: '',
			isErrorAddSkill: false,
			listSkillsForModalUpdate: [],
			isGettingListEndorsers: false,
			isCurrentAccount: false,
			loggedAccountData: JSON.parse(this.loggedAccount),
			loggedAccountId: 0
		}
	},

	created: function () {
		this.loggedAccountId = this.loggedAccountData.id
		setTimeout(function() {
			if (this.mode == 'nonprofit')
				this.isCurrentAccount = true
			this.loadItems()
		}.bind(this), 10)
	},

	methods: {
		loadItems: function (submit) {
			this.loading = true
			var url = this.resourceUrl
			this.$http.get(url).then(function (response) {
				var data = response.data
				this.items = data.items
				for (var i in this.items) {
					this.listNameSkills.push(this.items[i].name.toLowerCase())
				}
				this.loading = false
			}.bind(this));
		},

		addSkill: function () {
			if (!this.skillNameToAdd || this.loading) return
			if (this.listNameSkills.indexOf(this.skillNameToAdd.toLowerCase()) >= 0) {
				this.isErrorAddSkill = true
				return
			}
			this.isErrorAddSkill = false
			this.loading = true

			var url = this.resourceUrl
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
		}
	}
})