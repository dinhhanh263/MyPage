@extends('common.mypage_base')

@section('content')
	<main id="friends" class="container" ontouchstart>
		<div class="contents">
			<div class="contents-inner">
				<div class="contents-image">
					<img src="{{ asset('img/friends/img-1.jpg') }}" width="640" height="973">
				</div>
				<div class="contents-image">
					<img src="{{ asset('img/friends/img-2.jpg') }}" width="640" height="428">
				</div>
				<div class="sns">
					<div class="sns-inner">
						<div class="sns-icon">
							<div><a onclick="shareToLine()"><img src="{{ asset('img/friends/l.png') }}" width="93" height="93"></a></div>
							<div><a onclick="shareToFacebook()"><img src="{{ asset('img/friends/f.png') }}" width="93" height="93"></a></div>
							<div><a onclick="shareToTwitter()"><img src="{{ asset('img/friends/t.png') }}" width="93" height="93"></a></div>
							<div><a onclick="copyToClipboard()"><textarea id="copyTarget" readonly></textarea><img src="{{ asset('img/friends/c.png') }}" width="93" height="93"></a></div>
						</div>
					</div>
				</div>
				<div class="contents-image">
					<img src="{{ asset('img/friends/img-3.jpg') }}" width="640" height="1182">
				</div>
				<div class="contents-image">
					<img src="{{ asset('img/friends/img-4.jpg') }}" width="640" height="656">
				</div>
				<div class="contents-image">
					<img src="{{ asset('img/friends/img-5.jpg') }}" width="640" height="2376">
				</div>
				<div class="contents-image">
					<img src="{{ asset('img/friends/img-6.jpg') }}" width="640" height="32">
				</div>

				<div class="sns-bottom">
					<div class="sns-inner">
						<div class="sns-icon">
							<div><a onclick="shareToLine()"><img src="{{ asset('img/friends/line.png') }}" width="160" height="115"></a></div>
							<div><a onclick="shareToFacebook()"><img src="{{ asset('img/friends/fb.png') }}" width="160" height="115"></a></div>
							<div><a onclick="shareToTwitter()"><img src="{{ asset('img/friends/tw.png') }}" width="160" height="115"></a></div>
							<div><a onclick="copyToClipboard()"><textarea id="copyTarget" readonly></textarea><img src="{{ asset('img/friends/cp.png') }}" width="160" height="115"></a></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	<!-- Twitter JavaScriptSDK -->
	<script async src="//platform.twitter.com/widgets.js" charset="UTF-8"></script>

	<!-- Facebook JavaScriptSDK -->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.7";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>

	<!-- LINE JavaScriptSDK -->
	<script src="https://media.line.me/js/line-button.js?v=20140411" ></script>

	<script>
		function shareToCopy() {
			if ( window.confirm("URLをコピーしますか？") ) {
				var cp = new Clipboard("#copyTheURL");
				cp.on("success", function(e) {
					$('#notify').html('クリップボードにコピーしました。').addClass('notify success animated fadeIn').removeClass('hide fadeOut');
					setTimeout(function() {
						$('#notify').removeClass('fadeIn').addClass('fadeOut');
					} , 1800);
					var data = { myCode: "{{ $ary_customer['customerNo'] }}", eventName: "shareToCopy" };
					share_count(data);
				});
				cp.on("error", function(e) {
					$('#notify').html('クリップボードのコピーに失敗しました。').addClass('notify error animated fadeIn').removeClass('hide fadeOut');
					setTimeout(function() {
						$('#notify').removeClass('fadeIn').addClass('fadeOut');
					} , 1800);
				});
				cp = '';
			} else {
				console.log("URLのコピーをキャンセルした。");
			}
			return false;
		}

		function shareToFacebook() {
			if ( window.confirm("FacebookでURLをシェアしますか？") ) {
				var data = { myCode: "{{ $ary_customer['customerNo'] }}", eventName: arguments.callee.name };
				share_count(data);
				var url = 'https://www.facebook.com/sharer/sharer.php?u={{ $ary_invite['url'] }}&amp;src=sdkpreparse';
				window.open( url, "Facebookでシェアする", "width=500,height=500,scrollbars=1" );
			} else {
				//console.log("Facebookでシェアをキャンセルした。");
			}
			return false;
		}

		function shareToTwitter() {
			if ( window.confirm("TwitterでURLをシェアしますか？") ) {
				var data = { myCode: "{{ $ary_customer['customerNo'] }}", eventName: arguments.callee.name };
				share_count(data);
				var url = 'https://twitter.com/intent/tweet?text={{ $ary_invite['text'] }}';
				window.open( url, "Twiiterでシェアする", "width=500,height=500,scrollbars=1" );
			} else {
				//console.log("Twitterでシェアをキャンセルした。");
			}
			return false;
		}

		function shareToLine() {
			if ( window.confirm("LINEでURLをシェアしますか？") ) {
				var data = { myCode: "{{ $ary_customer['customerNo'] }}", eventName: arguments.callee.name };
				share_count(data);
				var url = 'http://line.me/R/msg/text/?{{ $ary_invite['text'] }}';
				location.href = url;
			} else {
				//console.log("LINEでシェアをキャンセルした。");
			}
			return false;
		}

		function shareToMail() {
			if ( window.confirm("メールでURLをシェアしますか？") ) {
				var data = { myCode: "{{ $ary_customer['customerNo'] }}", eventName: arguments.callee.name };
				share_count(data);
				location.href='mailto:?subject={{ $ary_invite['subject'] }}&body={{ $ary_invite['body'] }}'
			} else {
				console.log("Twitterでシェアをキャンセルした。");
			}
			return false;
		}

		function share_count(data) {
			$.ajax({
				type: "POST",
				url: "share_count.html",
				data: data,
				success: function(data, dataType) {
					//console.log("Ajax Success!");
				},
				error: function ( XMLHttpRequest, t, e) {
					console.error('Ajax Error:' + e);
				}
			});
			return false;
		}
		let copyTarget = document.getElementById('copyTarget');
		copyTarget.value = unescape('{{ $ary_invite['url'] }}');

		function copyToClipboard() {
			// コピー対象をJavaScript上で変数として定義する
			var copyTarget = document.getElementById(`copyTarget`)
			// コピー対象のテキストを選択する
			copyTarget.select();
			// 選択しているテキストをクリップボードにコピーする
			document.execCommand("copy");
			// コピーをお知らせする
			alert("クリップボードにコピーしました。");
		}
	</script>
@endsection
