import React, { Component } from 'react';
import Users from './Users';
import userStub from './users.json';
// import Content from './Content';
import $ from 'jquery';

class App extends Component {

    static apiUrl = 'http://192.168.99.100:8080';

    constructor(props) {
        super(props);

        this.state = {
            users: [],
            selectedUser: undefined
        };
    }

    componentDidMount() {
        this.getUsers();
    }

    getUsers() {
        this.setState({users:userStub})
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

        return (
            <main>
                <Users users={this.state.users} />

                <section  className="col-xs-8 panel panel-default">
                blaat
                {

                }
                </section>
            </main>
        );
    }
}

export default App;
