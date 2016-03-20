var Lock = function () {

    return {
        //main function to initiate the module
        init: function () {

             $.backstretch([
		        "/zbase/assets/metronic/img/bg/1.jpg",
		        "/zbase/assets/metronic/img/bg/2.jpg",
		        "/zbase/assets/metronic/img/bg/3.jpg",
		        "/zbase/assets/metronic/img/bg/4.jpg"
		        ], {
		          fade: 1000,
		          duration: 8000
		      });
        }

    };

}();