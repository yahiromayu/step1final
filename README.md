＊＊＊仕様＊＊＊
投稿は名前と本文からなる。->名前(name)、本文(comment)
投稿はPOST送信のみ受け付ける。->getについては記述しない
投稿データはデータベースへ保存する。->入力必須なものがPOSTされたらデータベースへ保存
新しい投稿ほど上に表示される。->time(TIMESTANP)に関してDESCで並べる
HTMLタグが入力された場合はエスケープする。->htmlspecialchars()でエスケープ
本文の改行コードは<\br />タグへ変換する。->nl2br()で改行を反映する

＊＊＊追加要素＊＊＊
名前、本文のほかに「タイトル」「メールアドレス」の入力欄も設けた
コメントがない場合は投稿できない。
名前、タイトルがない場合、それぞれ「名無しさん」「タイトルなし」になる
荒らし対策としてipアドレスをデータベースに保存する
ヘッダの$blacklistにipを入力すれば、そのipから投稿をさせず、そのipから投稿されたものは表示しない
＊ここの処理に関して送信ボタンを押すとPOSTが反映されてしまっていたので、送信ボタンを隠すことで解決する(後から追加したのでソースちょっとぐちゃぐちゃ)

＊＊＊余裕があれば追加したい要素＊＊＊
メールアドレスを使って削除ができるようにしたい(どうやって特定のデータを参照して消すか)
-同じくメールアドレスを使って一度投稿したものを編集をできるようにしたい
-メールアドレスでなくてもユニークな文字列をキャッシュに保存して特定できればよい？apc_store
長すぎる場合はページ分割(書き込み50ずつとか)、データベース参照部分でLIMITを使えばいけるはず
安価貼れるようにしたい→番号振りとリンク貼りつけ、時間かかりそう
他の人のレスのタイトルクリックで「Re:～」をタイトル欄に自動で入力したい→調べた感じだとJS必要みたい