import React, { Component } from 'react';

class User extends Component {

    onClick = (e) => {

    }

    render() {
        const { user:{ fullName, userName, avatar, id } } = this.props;
        return(
            <li>
                { fullName } - { userName }
                <img className="avatar-image" src={avatar} alt="users icon"/>
                <button onClick={()=>{this.props.selectUser(id)}}>{ userName }</button>
            </li>
        );
    }
}

export default User;
