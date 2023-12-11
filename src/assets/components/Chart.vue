<script>
import {Scatter} from 'vue-chartjs';
import moment from 'moment';
import pl from 'moment/locale/pl';
import 'chartjs-adapter-moment';
import chart from '../mixins/chart';

moment.locale('pl');
export default {
    name: 'Chart',
    extends: Scatter,
    mixins: [chart],
    props: {
        data: {
            type: Object,
            required: true
        }
    },
    mounted() {
        this.init(this.data);
    }
};
</script>
<style scoped>
</style>