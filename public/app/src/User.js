import React, { Component } from 'react';

class User extends Component {

    render() {
        const { user:{ fullName, userName, avatar, id } } = this.props;

        return(
            <li className="users-list-user">
                <span title={userName}>{fullName ? fullName : userName}</span>
                <img className="users-list-user-avatar" src={avatar} alt="users icon"/>
                <button onClick={()=>{this.props.selectUser(id)}}>{ userName }</button>
            </li>
        );
    }
}

export default User;
