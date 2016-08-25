import React, { Component } from 'react';
import User from './User';

class Users extends Component {

    constructor(props) {
        super(props);
    }

    render() {

        var users = [
            {avatar: 'https://avatars3.githubusercontent.com/u/21196816?v=3&amp;s=460', username: 'username'},
            {avatar: 'https://avatars3.githubusercontent.com/u/21196816?v=3&amp;s=460', username: 'ahilsden'}
            ];

        return(
            <div className="col-xs-3 col-sm-3 col-md-3 col-lg-3 panel panel-default">
                <h2>List of Users</h2>

                <ul>
                {users.map((user, index) =>
                    <User key={index}
                        avatar={user.avatar}
                        username={user.username}
                    />
                )}
                </ul>
            </div>
        );
    }
}

export default Users;