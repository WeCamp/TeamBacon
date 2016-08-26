import React, { Component } from 'react';
import User from './User';

class Users extends Component {

    render() {
        const { users, selectUser } = this.props;

        return(
            <section className="col-xs-4 panel panel-default">
                <h2>List of Users</h2>

                <ul className="list-of-users">

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
