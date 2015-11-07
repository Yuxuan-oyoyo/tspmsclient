<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 11/5/2015
 * Time: 11:55 PM
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>[480] chat prototype</title>
    <!-- Not present in the tutorial. Just for basic styling. -->
    <link rel="stylesheet" href="<?=base_url()?>css/chat/base.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.13.0/react.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.6.15/browser.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/0.3.2/marked.min.js"></script>
</head>
<body>

<div id="container"></div>
<script type="text/babel">
    var CurrentUser = "1"
    var get_data = []

    var LeftUser = React.createClass({
        render: function() {
            var DisplayName = (this.props.data.user1 == CurrentUser) ? this.props.data.user2: this.props.data.user1;
            var c_id = this.props.c_id;

            if(c_id == this.props.data.chatID)
            {
                return (
                    <li className="thread-list-item active" onClick={this.props.handleClickOnLeftUser.bind(null, this.props.data)}>
                        <h5 className="thread-name"> {DisplayName} </h5>
                        <div className="thread-time">
                            {this.props.data.lastMsgTimeStamp}
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
                            {this.props.data.lastMsgTimeStamp}
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

            return (

                <li className="message-list-item">
                    <h5 className="message-author-name"> {this.props.msg.author} </h5>
                    <div className="message-time"> </div>
                    <div className="message-text"> {this.props.msg.content}</div>
                </li>
            )
        }

    })

    var RightMessageComposerBox = React.createClass({
        //WORKINGON
        getInitialState: function() {


            return {text: ''};
        },
        handleChange: function(event) {
            this.setState({text: event.target.value});
        },
        handleKeyDown: function(evt) {
            if (evt.keyCode == 13 ) { //code 13 enter
                event.preventDefault()
                var text = this.state.text.trim();
                if (text)
                {

                    console.log("handle composer [enter]")
                    var threadID = this.props.thread.chatID
                    var datetime = new Date() / 1000;
                    var url = "<?=base_url()."chat/write"?>";
                    $.ajax({
                        type: "GET",
                        data: {chatID:threadID, timeStamp: datetime, author: CurrentUser ,content: text },
                        url : url,
                        success: function(msg){
                            console.log("success");
                        }

                    })
                    // push to server
                    // callback to server to refresh
                    //console.log(JSON.stringify(this.state.refreshFunc, null, 4));
                    this.props.refreshFunc();

                }
                this.setState({text: ''})

            }
        },
        render: function(){


            return(
                <div >
                    <textarea placeholder="Type message here" className="message-composer" value={this.state.text} onChange={this.handleChange} onKeyDown={this.handleKeyDown}/>
                </div>
            )
        }
    })

    var RightMessageBox = React.createClass({


        render: function() {
            var parentProps = this.props;
            //console.log("user 1 is " + this.props.chat.user1)
            var j = (this.props.chat.user1 == CurrentUser) ? this.props.chat.user2: this.props.chat.user1;

            // WORKINGON
            var sortedMessages;
            var msgNodes;




            if (this.props.chat.user1 !== undefined) {
                sortedMessages = this.props.chat.messages.sort(function(a,b){
                    return a.timeStamp - b.timeStamp
                })
                msgNodes = sortedMessages.map(function(msg){
                    return (
                        <RightMessage msg={msg} key={msg.msgID}> </RightMessage>
                    )
                })
            } else {
                msgNodes = "not selected yet"
            }
            return(
                <div className="message-section">
                    <h3 className="message-thread-heading">{j}</h3>
                    <ul className="message-list" ref="messageList">
                        {msgNodes}
                    </ul>
                    <RightMessageComposerBox thread={this.props.chat} refreshFunc={this.props.refreshFunc} />

                </div>
            )
        },
        componentDidUpdate: function() {
            this._scrollToBottom();
        },
        _scrollToBottom: function() {
            var ul = this.refs.messageList.getDOMNode();
            ul.scrollTop = ul.scrollHeight;
        },
    })

    // PARENT component
    var MainChat = React.createClass({
        getInitialData: function(){
            var url = "<?=base_url()."chat/get/"?>"+CurrentUser;
            //var url = "http://localhost:8000/ws_a.php";

            $.get(url, function(data, status) {
                this.setState({chats: data})
            }.bind(this))
            //console.log(this)
        },
        getUnreadCount: function(){

            this.setState({unreadCount: this.state.chats.length})
        },
        tick: function(){
            this.getInitialData()
            this.getUnreadCount()
        },
        componentDidMount: function(){
            this.getInitialData();
            this.getUnreadCount();
            this.interval = setInterval(this.tick, 2000);
            //console.log("component did mount")
        },
        getInitialState: function() {
            return {
                chatId : "",
                chats : [],
                unreadCount : 0,
                //chats: this.props.chats
            };
        },
        handleClickOnLeftUser: function(data){
            //console.log("handleClickOnLeftUser");
            // console.log("chat id is");
            // console.log(data.chatID);
            this.setState({chatID: data.chatID});
        },

        render: function() {

            //console.log("main:render")
            //console.log(this.props.chats);


            var theThreadIWantToPass = {};
            for(var i = 0; i < this.state.chats.length; i++)
            {
                //console.log("chat: " + this.state.chats[i].chatID);
                if (this.state.chats[i].chatID === this.state.chatID) {
                    theThreadIWantToPass = this.state.chats[i];
                    break;
                }
            }
            var unread = this.state.unreadCount === 0 ?
                <span>Unread threads: 0 </span>
            :   <span>Unread threads: {this.state.unreadCount} </span>;

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
                            chat={theThreadIWantToPass}
                            refreshFunc={this.getInitialData}
                            />
                    </div>
                </div>
            );
        }
    });


    console.log("test for jquery")
    console.log(typeof jQuery)




    React.render(<MainChat />, document.getElementById('container'));
</script>
</body>
</html>

