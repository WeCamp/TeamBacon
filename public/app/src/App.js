import React, { Component } from 'react';
import Users from './Users';
import Content from './Content';

class App extends Component {

  render() {

    return (
        <div className="container">
            <div className="row">
                <h1>6 Degrees of Bacon</h1>
                <Users />
                <Content />
            </div>
        </div>
    );
  }
}

export default App;
