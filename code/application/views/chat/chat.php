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
    <link rel="stylesheet" href="<?=base_url()?>css/chat/chat.css" />
    <script src="https://fb.me/react-with-addons-0.14.3.js"></script>
    <script src="https://fb.me/react-dom-0.14.3.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.6.15/browser.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/1.7.2/moment.min.js"></script>
</head>
<body>
<?php
$class = [
    'dashboard_class'=>'',
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
    var UserId = <?php echo json_encode($this->session->userdata('internal_uid')); ?>;
    var C_UserName = <?php echo json_encode($this->session->userdata('Customer_username')); ?>;
    var RealName = <?php echo json_encode($this->session->userdata('internal_realname')); ?>;
    var C_RealName = <?php echo json_encode($this->session->userdata('Customer_realname')); ?>;
    //console.log("x")
    //console.log(RealName)


    var get_data = [];
    var LeftUser = React.createClass({
        // @formatter:off
        componentDidMount: function()
        {

        },
        render: function() {
            //console.log("hooman")
            //console.log(UserType)

            var DisplayName = ""
            if(UserType == "PM")
            {
                DisplayName = (this.props.data.user1 == UserName) ? this.props.data.user2 : this.props.data.user1;
            }else
            {
                console.log("hoomanin")
                console.log(this.props.data)
                console.log(this.props.data.user1)
                //console.log(this.props.data.user2)
                //console.log(C_UserName.toLowerCase())

                DisplayName = (this.props.data.user1.toLowerCase() == C_RealName) ? this.props.data.user1 : this.props.data.user2;
            }



            var c_id = this.props.c_id;

            var ts = this.props.data.lastMsgTimeStamp;
            var d = new Date(0); // The 0 there is the key, which sets the date to the epoch
            d.setUTCSeconds(ts);
            var dates = moment(d).format('LL');


            //console.log("puffy")
            //console.log(this.props.data)
            var counter = 0;
            var messages = this.props.data["messages"]

            for(var msg in messages)
            {
                //console.log(messages)
                if(UserType == "PM" && messages[msg].to_pm =="1" && messages[msg].seen == false)
                {
                    //console.log(messages[msg].seen)
                    counter = counter + 1
                }
                else if (UserType != "PM" && messages[msg].to_pm == "0" && messages[msg].seen == false)
                {
                    counter = counter + 1
                }
            }
            //console.log("puzzy")
            var img_url = '<?=base_url()?>'+'img/avatars/'+(DisplayName.substring(0, 1)).toUpperCase()+'.png'


            var left_side_message = this.props.data.lastMessage;

            //console.log("puzzy")
            //console.log(this.props.data)


            if((this.props.data.lastMessage == ""))
            {
                var is_file = this.props.data.is_file
                var file_name = is_file.substring(0, is_file.indexOf('^'))
                left_side_message = file_name
            }
            else((this.props.data.lastMessage).length>=35)
            {

                left_side_message = left_side_message.substring(0,33) +' ...';
            }



            if(c_id == this.props.data.chatID)
            {
                return (
                     <div className="media conversation msg-active" onClick={this.props.handleClickOnLeftUser.bind(null, [this.props.data, counter])}>
                        <a className="pull-left" href="#">
                            <img className="media-object profile-img" src={img_url} alt={DisplayName} />
                        </a>
                        <div className="media-body">
                         <h5 className="media-heading">{DisplayName} &nbsp;  <small className="pull-right"><i className="fa fa-clock-o"/>&nbsp;{dates}</small></h5>
                            {left_side_message}
                         </div>
                     </div>
                );
            }
            else
            {

               if(counter==0){
                    return (
                        <div className="media conversation" onClick={this.props.handleClickOnLeftUser.bind(null, [this.props.data, counter])}>
                           <a className="pull-left" href="#">
                                <img className="media-object profile-img" src={img_url} alt={DisplayName} />
                          </a>
                          <div className="media-body">
                          <h5 className="media-heading">{DisplayName} &nbsp;  <small className="pull-right"><i className="fa fa-clock-o"/>&nbsp;{dates}</small></h5>
                               {left_side_message}
                          </div>
                        </div>
                    );
                }else{
                     return (
                        <div className="media conversation" onClick={this.props.handleClickOnLeftUser.bind(null, [this.props.data, counter])}>
                           <a className="pull-left" href="#">
                           <span className="new-msg badge" >{counter}</span>
                            <img className="media-object profile-img" src={img_url} alt={DisplayName} />
                          </a>
                          <div className="media-body">
                          <h5 className="media-heading">{DisplayName} &nbsp;  <small className="pull-right"><i className="fa fa-clock-o"/>&nbsp;{dates}</small></h5>
                               {left_side_message}
                          </div>
                        </div>
                    );
                }
            }
        }
    });

    // WORKINGON
    var LeftUserList = React.createClass({
        componentDidUpdate: function(){
            if(this.props.first_load == 1)
            {
                //console.log("LUL")
                //console.log(this.props.chats)
                this.props.chatIDHandler(0, this.props.chats)
            }
        },
        render: function() {
            //console.log("= = = LeftUserList Render = = =")
            var current_id = this.props.chat_id

            var parentProps = this.props;


            this.props.chats.sort(function(a, b)
            {
                var x = a["messages"].length-1
                var y = a["messages"][x].timestamp

                var i = b["messages"].length-1
                var j = b["messages"][i].timestamp

                return j - y
            });




            var userNodes = this.props.chats.map(function(data){
                return (
                    <LeftUser data={data}
                        handleClickOnLeftUser={parentProps.clickFunc}
                        handleUnread={parentProps.unreadFunc}
                        key={data.chatID}
                        c_id={current_id}> </LeftUser>
                )
            })








            //console.log("= = = = = = = = = = = = = = = = = = = = = = ")

            return (
                 <div className="msg-wrap-left">
                    {userNodes}
                </div>

            );
        }
    });







    var RightMessage = React.createClass({
        render: function(){
            var dateString = moment.unix(this.props.msg.timestamp).format(" MM/DD/YYYY HH:mm");


            var msg = this.props.msg;
            // @formatter:off

            if(msg.is_file != "0")
            {
                //console.log("guess who?")
                //console.log(msg);

                // WARNING: abit hackish here we splitted by ^

                var is_file = msg.is_file
                var file_name = is_file.substring(0, is_file.indexOf('^'))
                var hyper_link = is_file.substring(is_file.indexOf('^')+1)

                //console.log(file_name)
                //console.log(hyper_link)



                 if((UserType == "PM" && this.props.msg.to_pm == 1) || (UserType != "PM" && this.props.msg.to_pm == 0))
                    {
                        // this message is for me (left side)
                            return (
                                <div className="media">
                                    <div className="media-body ">

                                        <h5 className="media-heading">{this.props.msg.author}&nbsp; <small><i className="fa fa-clock-o"/>{dateString}</small> </h5>
                                        <div className="direct-chat-text ">
                                            { msg.content }
                                          File: <a href={hyper_link}> {file_name} </a><br/>
                                        <small><i>(Click file name to download)</i></small>
                                        </div>
                                    </div>
                                </div>
                            );
                    }
                    {
                        // else not for me
                         return (
                            <div className="media">
                                    <div className="media-body right">

                                        <h5 className="media-heading pull-right"> <small><i className="fa fa-clock-o"/>{dateString}</small>&nbsp;{this.props.msg.author}  </h5>
                                        <div className="direct-chat-text pull-right">
                                            { msg.content }
                                          File: <a href={hyper_link}> {file_name} </a><br/>
                                        <small><i>(Click file name to download)</i></small>
                                        </div>
                                    </div>
                                </div>
                            );

                    }

            }
            else
            {
                 if((UserType == "PM" && this.props.msg.to_pm == 1) || (UserType != "PM" && this.props.msg.to_pm == 0))
                    {
                            return (
                                 <div className="media">
                                    <div className="media-body">
                                        <h5 className="media-heading "> {this.props.msg.author}&nbsp;<small><i className="fa fa-clock-o"/>{dateString}</small> </h5>
                                         <div className="direct-chat-text">
                                          {this.props.msg.content}
                                        </div>
                                    </div>
                                </div>
                            );
                        // this message is for me (left side)
                    }
                    {
                        // else not for me
                         return (
                          <div className="media">
                                    <div className="media-body right">

                                        <h5 className="media-heading pull-right"> <small><i className="fa fa-clock-o"/>{dateString}</small>&nbsp;{this.props.msg.author} </h5>
                                         <div className="direct-chat-text pull-right ">
                                          {this.props.msg.content}
                                        </div>
                                    </div>
                                </div>
                            );

                    }


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
                var get_pm_id = 0;
                var get_to_the_pm = 0;

                if(this.props.thread["messages"][0]["pm_id"] != UserId)
                {
                    get_to_the_pm = 1;
                    get_pm_id = this.props.thread["messages"][0]["pm_id"];
                }
                this.props.scroller(1)
                this.props.fast_msg(text)




                var datetime = new Date() / 1000;
                var url = "<?=base_url()."chat/write"?>";
                //console.log(threadID + " " + CurrentUser)

                $.ajax({
                    type: "GET",
                    data: {chatID:threadID, timeStamp: datetime, author: CurrentUser ,content: text, to_the_pm: get_to_the_pm, pm_id: get_pm_id },
                    url : url,
                    success: function(){
                        console.log("success");
                        console.log(data);
                    },
                    error: function()
                    {
                        //console.log("errback");
                    }

                })
                // push to server
                // callback to server to refresh
                //console.log(JSON.stringify(this.state.refreshFunc, null, 4));
                //this.props.refreshFunc();
                //this.forceUpdate();
                //this.replaceState({text: '
            }
            //console.log("im here")
            //console.log(this.state.text)
            //this.setState({text:''})
            //console.log(this.state.text.charCodeAt(0))
            //console.log("do i get here?")
            this.setState({text:''})
            this.forceUpdate();

        },
        handleKeyDown: function(evt) {
            /*
            if (evt.keyCode == 13 ) { //code 13 enter
                event.preventDefault()

                this.handleWrite();
            }
            */

        },
        render: function(){

            //console.log(this.props.filey)

            // if user uploaded file
            //<textarea rows="4" placeholder={up_text} className=" form-control" value={this.state.text} onChange={this.handleChange} onKeyDown={this.handleKeyDown} disabled/>

            if (this.props.filey !== null)
            {
                var up_text = "Upload " + this.props.filey;
                 // TODO: CZ to fix ui -> make <span> look cool again
                // <textarea rows="4" placeholder={up_text} className=" form-control" value={this.state.text} onChange={this.handleChange} onKeyDown={this.handleKeyDown} disabled/>
                return(
                    <div>
                        <textarea rows="4" placeholder={up_text} className=" form-control" disabled/>
                        <div>
                            <FileForm scroller={this.props.scroller} fast_msg={this.props.fast_msg} threadID={this.props.thread.chatID} text_handler={this.handleText} filey={this.props.filey} fu_handler={this.props.fu_handler} fu_refresher={this.props.fu_refresher} />
                        </div>

                    </div>

                )
            }
            else
            {
                // if user did not upload file

                return(
                    <div >
                        <textarea rows="4" placeholder="Type message here" className="form-control" value={this.state.text} onChange={this.handleChange} onKeyDown={this.handleKeyDown}/>
                        <div>
                            <FileForm threadID={this.props.thread.chatID} text_handler={this.handleText} fu_handler={this.props.fu_handler}/>
                        </div>
                        <div>
                            <button  className="col-md-2 text-right btn btn-primary pull-right" onClick={this.handleWrite} type="button">Reply </button>
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

            // file data test
            // console.log("opa")
            // console.log(this.state.data_uri)

            var form = document.getElementById('upload_form')
            var u_text = document.getElementById('user_text').value
            //alert(u_text)

            //this.props.fast_msg("File: "+this.state.f_name+this.state.extension)

            var url = "<?=base_url()."chat/write"?>";
            var threadID = this.props.threadID;
            $.ajax({
                url: url,
                type: "POST",
                asyn: true,
                data: {
                        user_msg: u_text,
                        test_data:this.state.data_uri,
                        ext: this.state.extension,
                        f_name: this.state.f_name,
                        author: CurrentUser,
                        chatID:threadID,
                      },
                success: function(data) {
                    // do stuff
                    this.props.text_handler();
                }.bind(this),
                error: function() {
                    // do stuff

                }.bind(this)
            });
            this.props.scroller(1)
            //this.props.fast_msg(this.state.f_name+"^https://s3-ap-southeast-1.amazonaws.com/test-upload-file/7f27d_wujing.jpg")
            this.props.fast_msg("uploading file..")
            this.props.text_handler();
            this.props.fu_refresher();


        },
        handleFile: function(e)
        {
            var self = this;
            var reader = new FileReader();
            var file = e.target.files[0];
            var ext = e.target.files[0].name.split('.').pop().toLowerCase()
            //var file_name =      e.target.files[0].name.split('.')[0]
            var file_name = e.target.files[0].name

            this.props.fu_handler(e.target.files[0].name);

            console.log("after")
            reader.onload = function(upload) {

                //document.getElementById("image").src = upload.target.result;

                /*
                $('#image')
                    .attr('src', upload.target.result)
                    .width(150)
                    .height(200);
                */
                self.setState({
                    data_uri: upload.target.result,
                    extension: ext,
                    f_name: file_name
                });
            }
            reader.readAsDataURL(file);

        },
        // TODO: CZ make textarea cool again
        render: function() {
            // @formatter:off
            //var stylish = "display:none; max-width: 160px; max-height: 120px; border: none;"

            if(this.props.filey != null)
            {
                // TODO: cz make preview nice please
                return (
                        <div>
                        <img id="image" />
                    <form id="upload_form" onSubmit={this.handleSubmit} encType="multipart/form-data">
                        <input type="hidden" id="user_text"/>
                       <span className="col-md-2  btn btn-default pull-left btn-file">
                                Add File <input type="file" onChange={this.handleFile}/>
                        </span>
                        <input className=" col-md-2 text-right btn btn-primary pull-right"  type="submit" value="Reply" />
                    </form>
                    </div>
                );
            }
            else
            {
                return (
                    <form onSubmit={this.handleSubmit} encType="multipart/form-data">
                        <span className="col-md-2  btn btn-default pull-left btn-file">
                                Add File <input type="file" onChange={this.handleFile}/>
                        </span>
                        <input className=" col-md-2 text-right btn btn-primary pull-right" type="hidden" value="Reply" />
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
                     <div className="col-md-10">
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
                    //console.log(this.props.chat.messages)
                    msgNodes = this.props.chat.messages.map(function(msg){
                        return (
                            <RightMessage msg={msg} key={msg.msgID}> </RightMessage>
                        )

                    })
                    this.state.msgnodes = msgNodes;
                    //console.log("msgnodes")
                    //console.log(this.state.msgnodes)

                } else {
                    msgNodes = "not selected yet"
                }
                return(
                    <div  className="col-md-8">
                        <div>
                            <h3 className="message-thread-heading">{j}</h3>

                        </div>

                        <div className="msg-wrap-right" onScroll={this.handleScroll} id="msglist" ref="messageList">
                           <div className="col-md-12">
                            {this.state.msgnodes}
                            </div>
                        </div>
                        <RightMessageComposerBox scroller={this.props.scroller} fast_msg={this.props.fastMsg} filey={this.props.filey} fu_handler={this.props.fu_handler} fu_refresher={this.props.fu_refresher} thread={this.props.chat} refreshFunc={this.props.refreshFunc} />

                    </div>
                )

            }
        },
        handleScroll:function(event: Object){
            /*
            console.log("dance")
            var ul=this.refs.messageList;
            var sh = ul.scrollHeight
            console.log(scrollTop)
            //console.log(sh)
            //console.log(event.nativeEvent.contentOffset.y);
            */
        },
        componentDidUpdate: function() {
            //console.log("jig")
            //console.log(this.props.scroll)
            if(this.props.scroll == 1)
            {
                //console.log("hre")
                this._scrollToBottom();
            }
            //preventDefault();
        },
        _scrollToBottom: function() {

            //var ul = this.refs.messageList.getDOMNode();
            var ul = this.refs.messageList;
            ul.scrollTop = ul.scrollHeight;
            this.props.scroller(0)
            //console.log("xxx")
            //console.log(ul.scrollHeight)

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
                    //console.log("!")
                    //console.log(data[0])
                    //console.log("?")

                    //console.log("!")
                    //console.log(data)
                    //console.log("?")

                    var counter = 0
                    var am_i_pm = 0
                    if(UserType == "PM")
                        am_i_pm = 1;



                    for(var thread in data)
                    {
                        var messages = data[thread]["messages"]
                        for(var msg in messages)
                        {
                            var m = messages[msg].seen
                            var tpm = messages[msg].to_pm
                            if(UserType == "PM" && m == false && tpm == 1 )
                            {
                                counter = counter + 1;
                            }
                            else if(UserType != "PM" && m == false && tpm == 0)
                            {
                                counter = counter + 1;
                            }
                            //console.log(messages[msg].seen)
                        }
                    }

                    if(this.state.just_on == true)
                    {

                        this.setState({unread:counter, just_on: false, chats: data, chatID: data[0].chatID, just_on: false})
                    }
                    else
                    {
                        this.setState({chats: data, unread:counter })
                    }

                    setTimeout(this.getInitialData, 1000)
                }.bind(this),
                error: function(XMLHttpRequest,textStatus, errorThrown)
                {
                    console.log("Polling error")
                    setTimeout(this.getInitialData, 1000);
                }.bind(this)
            })




        },
        fileUploadHandler: function(data)
        {
            this.setState({file:data})

        },
        scroller:function(data)
        {
            //console.log("scroller function")
            this.setState({scroll:data})
        },
        chatIDHandler:function(data, data1)
        {
            //console.log("scroller function")
            //console.log("love")
            //console.log(data)
            //console.log(data1)
            this.setState({firstload:0, chatID:data1[0].chatID})
        },
        fileUploadRefresher: function()
        {
            this.setState({file:null})
        },
        handleUnread:function(data){

            console.log("Handle Unread");
            var update_unread = this.state.unread - data;

            this.setState({unread: update_unread})
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
                unread : 0,
                file: null,
                theThreadIWantToPass: {},
                just_on: true,
                scroll: 1,
                firstload: 1,
                //chats: this.props.chats
            };
        },
        handleClickOnLeftUser: function(arr){
            //console.log("handleClickOnLeftUser");
            // console.log("chat id is");
            // console.log(data.chatID);
            //console.log(data);
            this.fileUploadRefresher()
            //console.log("sphere")
            var data = arr["0"]
            //console.log(data)

            var to_pm = 0
            if(UserType == "PM")
                to_pm = 1

            this.scroller(1)

            if(arr[1] > 0)
            {
                var pm_id = data["messages"][0]["pm_id"]
                var c_id = data["messages"][0]["customer_id"]
                var url = "<?=base_url()."chat/readmsg/"?>";
                $.ajax({
                    type: "GET",
                    data: {pmid: pm_id, cid: c_id, topm: to_pm},
                    url : url,
                    success: function(data)
                    {
                        console.log("Handle clicks success")
                        //this.setstate
                        //setTimeout(this.getInitialData, 3000)
                    }.bind(this),
                    error: function(XMLHttpRequest,textStatus, errorThrown)
                    {
                        console.log("Handle clicks error")
                        //setTimeout(this.getInitialData, 5000);
                    }.bind(this)
                })
            }
            // database needs c_id, pm_id, to_pm

            if(data == "new_message")
            {
                this.setState({chatID: "new_message"});
            }
            else
            {
                console.log("rahs")
                console.log(data)
                console.log(data.chatID)
                var unread_count = this.state.unread - arr[1]
                this.setState({chatID: data.chatID, unread:unread_count});
            }

        },
        fast_msg: function(data){



            //if(this.state.theThreadIWantToPass == "0asdads") {
                console.log("in fast")
                var fast_thread = this.state.theThreadIWantToPass
                var last_msg_length = fast_thread["messages"].length - 1


                console.log("happy")
                console.log(fast_thread["messages"][last_msg_length])

                //var fast_msg = fast_thread["messages"][last_msg_length]
                var fast_msg = $.extend(true, {}, fast_thread["messages"][last_msg_length])

                if (UserType == "PM") {
                    fast_msg.author = RealName;
                }
                else {
                    fast_msg.author = C_RealName;
                }
                fast_msg.content = data
                fast_msg.timestamp = fast_msg.timestamp + 2
                fast_msg.msgID = fast_msg.msgID + 2
                fast_msg.is_file = "0"

                //console.log("hererinhito")
                console.log(fast_msg)
                //console.log(fast_msg["msgID"])

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

            //console.log("the thread i want to pass")
            //console.log(this.state.theThreadIWantToPass)

            var unread = <span>Unread threads: {this.state.unread} </span>;
            var user_name=  <?php echo json_encode($this->session->userdata('internal_username')); ?>;
            if(!user_name){
             user_name = <?php echo json_encode($this->session->userdata('Customer_username')); ?>;
            }
            if(this.state.chatID == "new_message")
            {
                // dead function fml
                //console.log("new_message")
                return (
                     <div className="col-md-offset-1 col-md-10 ">
                    <h1 className="page-header top-header">Chat Box - {user_name}</h1>
                       <div className="row">
                           <div className="col-md-3">

                                    <div className="thread-count">
                                        {unread}
                                    </div>
                                    <LeftUserList
                                        chat_id={this.state.chatID}
                                        chats={this.state.chats}
                                        clickFunc={this.handleClickOnLeftUser} // ***important
                                        first_load={this.state.firstload}

                                        />
                                </div>
                                <div  className="col-md-9">
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

                    </div>
                );
            }
            else
            {
                //console.log("render the thread I want to pass")
                //console.log(this.state.theThreadIWantToPass)
                return (
                     <div className="col-md-offset-1 col-md-10 ">
                    <h1 className="page-header top-header">Chat Box - {user_name}</h1>
                         <div className="row">
                                <div className="col-md-3">
                                    <div className="thread-count">
                                        {unread}
                                    </div>
                                    <LeftUserList
                                        chat_id={this.state.chatID}
                                        chats={this.state.chats}
                                        unreadFunc={this.handleUnread}
                                        clickFunc={this.handleClickOnLeftUser} // ***important
                                        first_load={this.state.firstload}
                                        chatIDHandler={this.chatIDHandler}
                                        />

                                </div>

                                    <RightMessageBox
                                        scroll = {this.state.scroll}
                                        scroller = {this.scroller}
                                        chat={this.state.theThreadIWantToPass}
                                        fastMsg={this.fast_msg}
                                        refreshFunc={this.getInitialData}
                                        chat_id = {this.state.chatID}
                                        clickFunc={this.handleClickOnLeftUser}
                                        fu_handler={this.fileUploadHandler}
                                        fu_refresher={this.fileUploadRefresher}
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

