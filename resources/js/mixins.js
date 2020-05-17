let fullName = {
  methods: {
    getFullName: function (arr) {
      return arr.lastname + ' ' + arr.firstname + ' ' + arr.patronymic;
    }
  }
};

let search = {
  methods: {
    inArr: function (val, arr) {
      if(!(arr instanceof Object)) return String(arr).toLowerCase().indexOf(val) > -1;
      return Object.keys(arr).some(key => this.inArr(val, arr[key]));
    }
  },
  computed: {
    filtered: function () {
      let data = this.json,
          search = this.searchWord && this.searchWord.toLowerCase();
      let filter = (val, arr) => {
        return arr.filter(row => {
          return Object.keys(row).some(key => {
            return this.inArr(search, row[key]);
          });
        });
      };

      if (search) {
        data = filter(search, data);
      }
      return data;
    }
  }
};


export { fullName, search };
