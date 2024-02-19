export default {
    computed: {
        checked: function () {
            let count = 0;
            this.positions.forEach(position => {
                if (position.checked) {
                    count++;
                }
            });
            return count;
        },
        progress: function () {
            if (this.positions.length === 0) {
                return 100;
            }
            return (this.checked / this.positions.length) * 100;
        },
        color: function () {
            if (this.checked === this.positions.length) {
                return 'light-green';
            }
            return 'light-blue';
        }
    }
};