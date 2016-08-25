import React, { Component } from 'react';
import User from './User';

class Users extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        var { users, selectUser } = this.props;

        return(
            <div className="col-xs-4 panel panel-default">
                <h2>List of Users</h2>

                <ul className="list-of-users">
                { users.map((user, index) =>
                    <User key={ index }
                          id={ user.id }
                          userName={ user.userName }
                          fullName={ user.fullName }
                          avatar={ user.avatar }
                          selectUser={ selectUser }
                    />
                ) }
                </ul>
            </div>
        );
    }
}

export default Users;
