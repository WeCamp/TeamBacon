import React, { Component } from 'react';

class User extends Component {

    render() {
        var { username, avatar } = this.props;

        return(
            <div>
                <li>
                    <span>
                        <img className="avatar-image" src={`${avatar}`} height="23" />
                        <a href="#">{username}</a>
                    </span>
                </li>
            </div>
        );
    }
}

export default User;