import React, { Component } from 'react';
import User from './User';

class Users extends Component {

    render() {
        const { users, selectUser } = this.props;

        return(
            <section className="col-xs-4 panel panel-default">
                <h3>List of Users</h3>

                <ul className="users-list">

                {
                  users.map((user) =>
                    <User
                      key={user.id}
                      user={user}
                      selectUser={selectUser}
                    />
                  )
                }
                </ul>
            </section>
        );
    }
}

export default Users;
