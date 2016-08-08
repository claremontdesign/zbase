<style type="text/css">
	.zbase-loader-wrapper{
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: rgba(0,0,0,0.4);
		z-index: 10000;
		height: 100%;
	}
	.zbase-loader{
		-webkit-animation: container-rotate 1.4s linear infinite;
		-moz-animation: container-rotate 1.4s linear infinite;
		-o-animation: container-rotate 1.4s linear infinite;
		animation: container-rotate 1.4s linear infinite;
		display: inline-block;
		position: absolute;
		top: 30%;
		left: 47%;
	}
	.zbase-loader-circle {
		margin: 60px auto;
		font-size: 10px;
		position: relative;
		text-indent: -9999em;
		border-top: 1px solid rgba(255, 255, 255, 0.2);
		border-right: 1px solid rgba(255, 255, 255, 0.2);
		border-bottom: 1px solid rgba(255, 255, 255, 0.2);
		border-left: 1px solid #ffffff;
		-webkit-transform: translateZ(0);
		-ms-transform: translateZ(0);
		transform: translateZ(0);
		-webkit-animation: load8 1.1s infinite linear;
		animation: load8 1.1s infinite linear;
	}
	.zbase-loader-circle,
	.zbase-loader-circle:after {
		border-radius: 50%;
		width: 10em;
		height: 10em;
	}
	@-webkit-keyframes load8 {
		0% {
			-webkit-transform: rotate(0deg);
			transform: rotate(0deg);
		}
		100% {
			-webkit-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}
	@keyframes load8 {
		0% {
			-webkit-transform: rotate(0deg);
			transform: rotate(0deg);
		}
		100% {
			-webkit-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}
</style>
<div class="zbase-loader-wrapper" style="display:none;">
	<div class="zbase-loader">
		<div class="zbase-loader-circle">Loading...</div>
	</div>
</div>