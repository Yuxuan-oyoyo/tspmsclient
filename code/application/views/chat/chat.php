<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 11/5/2015
 * Time: 11:55 PM
 */

$user_id = $user_id;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="<?=base_url()?>css/chat/base.css" />
    <script src="https://fb.me/react-with-addons-0.14.3.js"></script>
    <script src="https://fb.me/react-dom-0.14.3.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.6.15/browser.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/1.7.2/moment.min.js"></script>
    <script src="js/vendor/jquery.ui.widget.js"></script>
    <script src="js/jquery.iframe-transport.js"></script>
    <script src="js/jquery.fileupload.js"></script>
    <style>
        .headerStyle{
            color: grey;
            pointer-events:none;
            opacity:0.4;
        }
    </style>
</head>
<body>
<?php
$class = [
    'projects_class'=>'',
    'message_class'=>'active',
    'customers_class'=>'',
    'internal_user_class'=>'',
    'analytics_class'=>''
];
if($this->session->userdata('Customer_cid')){
    $this->load->view('common/customer_nav', $class);
}elseif ($this->session->userdata('internal_type')=='Developer') {
    $this->load->view('common/dev_nav', $class);
}else{
    $this->load->view('common/pm_nav', $class);
}
?>
<br>

<div id="container"></div>
<script type="text/babel">
    var CurrentUser = <?=$user_id?>;
    var UserName = <?php echo json_encode($this->session->userdata('internal_username')); ?>;
    var UserType = <?php echo json_encode($this->session->userdata('internal_type')); ?>;


    //console.log("utype")
    //console.log(UserType)

    var get_data = [];
    var LeftUser = React.createClass({
        render: function() {

            var DisplayName = (this.props.data.user1 == CurrentUser) ? this.props.data.user2: this.props.data.user1;
            var c_id = this.props.c_id;

            var ts = this.props.data.lastMsgTimeStamp;
            var d = new Date(0); // The 0 there is the key, which sets the date to the epoch
            d.setUTCSeconds(ts);
            /*
             var month = d.getMonth()+1;
             var date = d.getDate()+"."+month+"."+d.getFullYear();
             console.log("moo moo");
             */
            var dates = moment(d).format('LL');

            if(c_id == this.props.data.chatID)
            {
                return (
                    <li className="thread-list-item active" onClick={this.props.handleClickOnLeftUser.bind(null, this.props.data)}>
            <h5 className="thread-name"> {DisplayName} </h5>
                <div className="thread-time">
                {dates}
                </div>
                <div className="thread-last-message">
                {this.props.data.lastMessage}
            </div>
            </li>
            );
            }
            else
            {
                return (
                    <li className="thread-list-item" onClick={this.props.handleClickOnLeftUser.bind(null, this.props.data)}>
            <h5 className="thread-name"> {DisplayName} </h5>
                <div className="thread-time">
                {dates}
                </div>
                <div className="thread-last-message">
                {this.props.data.lastMessage}
            </div>
            </li>
            );
            }
        }
    });

    // WORKINGON
    var LeftUserList = React.createClass({

        render: function() {
            //console.log("= = = LeftUserList Render = = =")
            var current_id = this.props.chat_id

            var parentProps = this.props;
            var userNodes = this.props.chats.map(function(data){
                return (
                    <LeftUser data={data} handleClickOnLeftUser={parentProps.clickFunc} key={data.chatID} c_id={current_id}> </LeftUser>
                )
            })

            //console.log("= = = = = = = = = = = = = = = = = = = = = = ")

            return (
                <ul className="thread-list">
                {userNodes}
                </ul>

            );
        }
    });







    var RightMessage = React.createClass({
        render: function(){


            var msg = this.props.msg;
            // @formatter:off

            if(msg.is_file == 1)
            {
                var url = "<?=base_url()."chat/filesys/"?>";
                url = url.concat(msg.message_id);
                url = url.concat("/");
                url = url.concat(msg.content);

                return (

                    <li className="message-list-item">
                        <h5 className="message-author-name"> {this.props.msg.author} </h5>
                        <div className="message-time"> </div>
                        <div className="message-text"> <a href={url}> {this.props.msg.content} </a></div>
                    </li>
                )
            }
            else
            {
                return (

                    <li className="message-list-item">
                        <h5 className="message-author-name"> {this.props.msg.author} </h5>
                        <div className="message-time"> </div>
                        <div className="message-text"> {this.props.msg.content}</div>
                    </li>
                )
            }

        }

    })


    var RightNewMessageBox = React.createClass({

        getInitialState: function()
        {
            return {
                options: [],

            }
        },
        componentDidMount: function()
        {
            var url = "<?=base_url()."chat/conversation_list"?>";
            var data_arr = [];

            $.ajax({
                type: "GET",
                data: {woof:"one", meow:"two" },
                url : url,
                success: function(data){
                    console.log(data);
                    data_arr = data;
                    this.successHandler(data_arr)

                }.bind(this),
                error: function()
                {
                    console.log("errback");
                }

            })

        },
        successHandler: function(data)
        {
            // Causes react to have an error for some reason *fixed*
            var text = ' -- Name -- ';
            this.state.options.push(
                <option key="0" value="default_v" disabled> {text} </option>
            )

            var json = JSON.parse(data)

            if(UserType == "PM") {
                for (var i = 0; i < json.length; i++) {


                    var val = json[i].type
                    val = val + "_"
                    val = val + json[i].user_id

                    var name = json[i].title.concat(" ");
                    name = name.concat(json[i].f_name);
                    name = name.concat(" ");
                    name = name.concat(json[i].l_name)


                    this.state.options.push(
                        < option key={val} value={val} > {name} < /option >
                    )
                }
            }
            else
            {
                // else client trying to create new message
                //console.log(json)
                for (var i = 0; i < json.length; i++)
                {
                    var val = json[i].type
                    val = val + "_"
                    val = val + json[i].pm_id;

                    var name = json[i].name;

                    this.state.options.push(
                        <option key={val} value={val}> {name} </option>
                    )
                }

            }

            this.forceUpdate();

        },
        render: function(){


            return(
                <div>
                    <span> To: <select onChange={this.props.changeHandler} value={this.props.val}> {this.state.options}</select> </span>
                    <div>
                       <br />
                    </div>
                </div>

            )
        }
    })



    var RightMessageComposerBox = React.createClass({
        // @formatter:off
        getInitialState: function() {

            return  {
                        text: '',
                        current_user: '',
                    };
        },
        handleChange: function(event) {
            this.setState({text: event.target.value});
        },
        handleText: function()
        {
            this.setState({text: ''});
        },
        handleWrite: function()
        {
            var text = this.state.text.trim();
            if (text)
            {

                //console.log("handle composer [enter]")

                var threadID = this.props.thread.chatID


                this.props.fast_msg(text)

                var datetime = new Date() / 1000;
                var url = "<?=base_url()."chat/write"?>";
                console.log(threadID + " " + CurrentUser)
                $.ajax({
                    type: "GET",
                    data: {chatID:threadID, timeStamp: datetime, author: CurrentUser ,content: text },
                    url : url,
                    success: function(){
                        console.log("success");
                        console.log(data);
                        this.handleText()
                    },
                    error: function()
                    {
                        console.log("errback");
                    }

                })
                // push to server
                // callback to server to refresh
                //console.log(JSON.stringify(this.state.refreshFunc, null, 4));
                //this.props.refreshFunc();

            }
            this.setState({text: ''})
        },
        handleKeyDown: function(evt) {
            if (evt.keyCode == 13 ) { //code 13 enter
                event.preventDefault()

                this.handleWrite();
            }
        },
        render: function(){

            console.log(this.props.filey)

            // if user uploaded file

            if (this.props.filey !== null)
            {
                var up_text = "Upload " + this.props.filey;
                return(
                    <div >
                        <textarea placeholder={up_text} className="message-composer" value={this.state.text} onChange={this.handleChange} onKeyDown={this.handleKeyDown} disabled/>
                        <div>
                            <FileForm threadID={this.props.thread.chatID} text_handler={this.textHandler} filey={this.props.filey} fu_handler={this.props.fu_handler}/>
                        </div>

                    </div>

                )
            }
            else
            {
                // if user did not upload file

                return(
                    <div >
                        <textarea placeholder="Type message here" className="message-composer" value={this.state.text} onChange={this.handleChange} onKeyDown={this.handleKeyDown}/>
                        <div>
                            <FileForm threadID={this.props.thread.chatID} fu_handler={this.props.fu_handler}/>
                        </div>
                        <div>
                            <button onClick={this.handleWrite} type="button">Reply </button>
                        </div>
                    </div>

                )
            }
        }
    })

    var FileForm = React.createClass({
        getInitialState: function() {
            return {
                data_uri: null,
                extension: null,
                f_name: null,
            };
        },
        // prevent form from submitting; we are going to capture the file contents
        handleSubmit: function(e) {
            e.preventDefault();
            var url = "<?=base_url()."chat/write"?>";
            var threadID = this.props.threadID;
            $.ajax({
                url: url,
                type: "POST",
                data: {
                        test_data:this.state.data_uri,
                        ext: this.state.extension,
                        f_name: this.state.f_name,
                        author: CurrentUser,
                        chatID:threadID,
                      },
                success: function() {
                    // do stuff
                    this.props.text_handler();
                }.bind(this),
                error: function() {
                    // do stuff
                }.bind(this)
            });
            return false;
        },
        handleFile: function(e)
        {
            var self = this;
            var reader = new FileReader();
            var file = e.target.files[0];
            var ext = e.target.files[0].name.split('.').pop().toLowerCase()
            var file_name =      e.target.files[0].name.split('.')[0]

            this.props.fu_handler(e.target.files[0].name);
            console.log("after")
            reader.onload = function(upload) {
                self.setState({
                    data_uri: upload.target.result,
                    extension: ext,
                    f_name: file_name
                });
            }

            reader.readAsDataURL(file);
        },
        render: function() {
            // @formatter:off
            if(this.props.filey != null)
            {
                return (
                    <form onSubmit={this.handleSubmit} encType="multipart/form-data">
                    <input type="file" onChange={this.handleFile} />
                    <input type="submit" value="Reply" />
                    </form>
                );
            }
            else
            {
                return (
                    <form onSubmit={this.handleSubmit} encType="multipart/form-data">
                    <input type="file" onChange={this.handleFile} />
                    <input type="hidden" value="Reply" />
                    </form>
                );
            }
        },
    });

    var RightNewComposerBox = React.createClass({
        // @formatter:off
        getInitialState: function() {

            return  {
                        text: '',
                    };
        },
        handleChange: function(event) {
            this.setState({text: event.target.value});
        },
        handleText: function()
        {
            this.setState({text: ''});
        },
        handleWrite: function()
        {
            var text = this.state.text.trim();
            if (text)
            {


                var target_partner = this.props.value;
                var datetime = new Date() / 1000;

                //alert(target_partner)
                //alert(text)
                var url = "<?=base_url()."chat/new_write"?>";

                $.ajax({
                    type: "GET",
                    data: {partner:target_partner, timeStamp: datetime, author: CurrentUser ,content: text },
                    url : url,
                    success: function(){
                        console.log("success");
                        console.log(data);
                        this.handleText()
                        window.location.reload(true);
                    }.bind(this),
                    error: function()
                    {
                        console.log("errback");
                        //this.forceUpdate();
                        window.location.reload(true);
                    }

                })
                // push to server
                // callback to server to refresh
                //console.log(JSON.stringify(this.state.refreshFunc, null, 4));
                //this.props.refreshFunc();

            }
            this.setState({text: ''})
        },
        handleKeyDown: function(evt) {
            if (evt.keyCode == 13 ) { //code 13 enter
                event.preventDefault()

                this.handleWrite();

            }
        },
        render: function()
        {

            //console.log(this.props.value)
            return(
                <div >
                    <textarea placeholder="Type message here" className="message-composer" value={this.state.text} onChange={this.handleChange} onKeyDown={this.handleKeyDown}/>
                    <div>

                    </div>
                    <div>
                        <button onClick={this.handleWrite} type="button">Reply </button>
                    </div>
                </div>

            )
        }
    })

    var RightMessageBox = React.createClass({

        // @formatter:off
        getInitialState: function()
        {
            return {
                val: "default_v",
                just_on: true,
                msgnodes: [],

            }
        },
        msgUpdater: function(text)
        {

        },
        changeHandler: function(event)
        {
            console.log(event.target.value)
            this.replaceState({val : event.target.value})

        },
        render: function() {

            var parentProps = this.props;
            //console.log("user 1 is " + this.props.chat.user1)


            if(this.props.chat == "new_message")
            {
                // New Message

                return(
                    <div className="message-section">
                        <div>
                            <h3 className="message-thread-heading">New Message</h3>
                        </div>


                        <RightNewMessageBox thread={this.props.chat} refreshFunc={this.props.refreshFunc} changeHandler={this.changeHandler} val={this.state.val}/>
                        <ul className="message-list-disabled" >
                            <li className="headerStyle">  </li>
                        </ul>
                        <RightNewComposerBox fast_msg={this.props.fastMsg} value={this.state.val} handleState={this.props.clickFunc} />

                    </div>
                )

            }
            else
            {
                var j = (this.props.chat.user1 == CurrentUser) ? this.props.chat.user2: this.props.chat.user1;

                // WORKINGON
                var sortedMessages;
                var msgNodes;


                //MessageSorting
                //console.log(this.props.chat.user1);
                //console.log(this.props.chat.user2);
                //console.log("MessageSorting")


                if (this.props.chat.user1 !== undefined) {
                    /*
                    sortedMessages = this.props.chat.messages.sort(function(a,b){
                        //console.log("a: " + a.timestamp)
                        //console.log("b: " + b.timestamp)
                        return a.timeStamp - b.timeStamp
                    })
                    */
                    console.log(this.props.chat.messages)
                    msgNodes = this.props.chat.messages.map(function(msg){
                        return (
                            <RightMessage msg={msg} key={msg.msgID}> </RightMessage>
                        )

                    })
                    this.state.msgnodes = msgNodes;
                    console.log("msgnodes")
                    console.log(this.state.msgnodes)

                } else {
                    msgNodes = "not selected yet"
                }
                return(
                    <div className="message-section">
                        <div>
                            <h3 className="message-thread-heading">{j}</h3>
                            <div>
                                <button onClick={this.props.clickFunc.bind(null, "new_message")} className="message-thread-heading" type="button">+ New Message</button>
                            </div>
                        </div>

                        <ul className="message-list" ref="messageList">
                            {this.state.msgnodes}
                        </ul>
                        <RightMessageComposerBox fast_msg={this.props.fastMsg} filey={this.props.filey} fu_handler={this.props.fu_handler} thread={this.props.chat} refreshFunc={this.props.refreshFunc} />

                    </div>
                )

            }
        },
        componentDidUpdate: function() {
            if(this.props.chat != "new_message")
            {
                this._scrollToBottom();
            }
        },
        _scrollToBottom: function() {
            if(this.props.chat != "new_message")
            {
                //var ul = this.refs.messageList.getDOMNode();
                var ul = this.refs.messageList;
                ul.scrollTop = ul.scrollHeight;
            }
        },
    })

    // PARENT component
    var MainChat = React.createClass({
        getInitialData: function(){


            var url = "<?=base_url()."chat/get/"?>"+CurrentUser;

            /*
            $.get(url, function(data, status) {
                console.log(data[0]);
                //TODO: Sort array by timestamp before returning

                this.setState({chats: data, chatID: data[0].chatID})
            }.bind(this))
            */

            $.ajax({
                type: "GET",
                url: url,
                async: true,
                cache: false,
                timeout: 5000,
                success: function(data)
                {
                    console.log(data[0])
                    if(this.state.chatID != "new_message") {
                        if(this.state.just_on == true) {
                            this.setState({chats: data, chatID: data[0].chatID, just_on: false})
                        }
                        else
                        {
                            this.setState({chats: data })
                        }
                    }
                    setTimeout(this.getInitialData, 3000)
                }.bind(this),
                error: function(XMLHttpRequest,textStatus, errorThrown)
                {
                    console.log("Polling error")
                    setTimeout(this.getInitialData, 5000);
                }.bind(this)
            })




        },
        fileUploadHandler: function(data)
        {

            this.setState({file:data})

        },
        getUnreadCount:function(){
            // TODO
            this.setState({unreadCount: this.state.chats.length})
        },
        tick: function(){
            this.getInitialData()
            this.getUnreadCount()
        },
        componentDidMount: function(){
            this.getInitialData();
            //this.interval = setInterval(this.tick, 10000);
            //console.log("component did mount")
        },
        getInitialState: function() {
            return {
                chatId : "",
                chats : [],
                unreadCount : 0,
                file: null,
                theThreadIWantToPass: {},
                //chats: this.props.chats
            };
        },
        handleClickOnLeftUser: function(data){
            //console.log("handleClickOnLeftUser");
            // console.log("chat id is");
            // console.log(data.chatID);
            console.log(data);
            if(data == "new_message")
            {
                this.setState({chatID: "new_message"});
            }
            else
            {
                this.setState({chatID: data.chatID});
            }

        },
        fast_msg: function(data){

            var fast_thread = this.state.theThreadIWantToPass
            var last_msg_length = fast_thread["messages"].length - 1

            //var fast_msg = fast_thread["messages"][last_msg_length]
            var fast_msg = $.extend(true,{}, fast_thread["messages"][last_msg_length])


            // TODO: set author

            fast_msg.author = UserName;
            fast_msg.content = data
            fast_msg.timestamp = fast_msg.timestamp + 2
            fast_msg.msgID = fast_msg.msgID + 2

            console.log(fast_msg["msgID"])

            fast_thread["messages"].push(fast_msg);
            //this.setState({theThreadIWantToPass: fast_thread})

            this.setState({theThreadIWantToPass: fast_thread})



        },
        // @formatter:off
        render: function() {

            var theThreadIWantToPass = {};


            for(var i = 0; i < this.state.chats.length; i++)
            {
                //console.log("chat: " + this.state.chats[i].chatID);
                if (this.state.chats[i].chatID === this.state.chatID) {
                    this.state.theThreadIWantToPass = this.state.chats[i];
                    break;
                }
            }

            console.log("the thread i want to pass")
            console.log(this.state.theThreadIWantToPass)

            var unread = this.state.unreadCount === 0 ?
                <span>Unread threads: 0 </span>
                :  <span>Unread threads: {this.state.unreadCount} </span>;

            if(this.state.chatID == "new_message")
            {
                //console.log("new_message")
                return (
                    <div className="chatapp">
                        <div className="thread-section">
                            <div className="thread-count">
                                {unread}
                            </div>
                            <LeftUserList
                                chat_id={this.state.chatID}
                                chats={this.state.chats}
                                clickFunc={this.handleClickOnLeftUser} // ***important
                                />
                        </div>
                        <div>
                            <RightMessageBox
                                chat="new_message"
                                refreshFunc={this.getInitialData}
                                chat_id = {this.state.chatID}
                                clickFunc={this.handleClickOnLeftUser}
                                fu_handler={this.fileUploadHandler}
                                filey={this.state.file}
                                />
                        </div>
                    </div>
                );
            }
            else
            {
                console.log("render the thread I want to pass")
                console.log(this.state.theThreadIWantToPass)
                return (
                    <div className="chatapp">
                        <div className="thread-section">
                            <div className="thread-count">
                                {unread}
                            </div>
                            <LeftUserList
                                chat_id={this.state.chatID}
                                chats={this.state.chats}
                                clickFunc={this.handleClickOnLeftUser} // ***important
                                />
                        </div>
                        <div>
                            <RightMessageBox
                                chat={this.state.theThreadIWantToPass}
                                fastMsg={this.fast_msg}
                                refreshFunc={this.getInitialData}
                                chat_id = {this.state.chatID}
                                clickFunc={this.handleClickOnLeftUser}
                                fu_handler={this.fileUploadHandler}
                                filey={this.state.file}
                                />
                        </div>
                    </div>
                );
            }
        }
    });


    console.log("test for jquery")
    console.log(typeof jQuery)




    React.render(<MainChat />, document.getElementById('container'));
</script>
</body>
</html>

