import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app) => {
    app.component('index-entity-select-field', IndexField)
    app.component('detail-entity-select-field', DetailField)
    app.component('form-entity-select-field', FormField)
})
