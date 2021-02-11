import t from 'typy'

export default {
	methods: {
		catcher(_data, objname, func) {
			objname = null || objname
			let error = { has_error: false, msg: ''}

			try {
				if(objname != null) {
					if (t(_data, objname).isDefined == false) throw undefined
				}

				const data = func(_data)
				return { error, ...data }
			} catch(e) {
				console.log(`Something went wrong (${e})`)
				error.has_error = true
				error.msg = 'An error occured. Please try again later.'

				return { error }
			}

		}
	}
}