/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css'

// start the Stimulus application
// Loading librairies
import 'moment/locale/fr'
import 'bootstrap'
import 'masonry-layout'
import 'sweetalert2'
import 'tempusdominus-bootstrap-4'
import 'datatables.net-bs4'
import 'summernote'
import 'summernote/dist/lang/summernote-fr-FR'

// Loading written scripts

import './js/customizer'
import './js/custom/data-table'
import './js/custom/datetime-pickers'
import './js/script'
import './js/custom/editor-summernote'