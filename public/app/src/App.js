import React, { Component } from 'react';
import Users from './Users';
import Content from './Content';
import $ from 'jquery';

class App extends Component {

    static apiUrl = 'http://192.168.99.100:8080';

    constructor(props) {
        super(props);

        this.state = {
            users: [],
            selectedUser: undefined
        };

        this.getUsers();
    }

    getUsers() {
        $.ajax({
            url: App.apiUrl + '/api/users',
            type: 'GET'
        }).done(users => {
            this.setState({users});
        })
    }

    selectUser(userId) {
        $.ajax({
            url: App.apiUrl +'/api/users/' + userId,
            type: 'GET'
        }).done(selectedUser => {
            this.setState({selectedUser});
        });
    }

    render() {

        const { users } = this.state;

        return (

            <main>
                <ul>
                    {
                        users.map( user =>
                            <li key={id} id={id}>
                                { user.firstName } {user.fullName}
                                <img src={avatar.url} alt="users icon"/>
                            </li>
                        )
                    }
                </ul>
                <section>

                </section>

            </main>
        );
    }
}

export default App;
