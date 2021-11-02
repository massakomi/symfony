


class Aboutus extends React.Component {
    constructor(props) {
        super(props);
    }
    test () {
        return <span>xxx</span>
    }
    render() {
        //let name = 'Вася';
        //const family = 'Иванов';
        //let block = <div><span title={name} alt="22" className="mm">Мой заголовок</span><script type="text/javascript">alert(1); console.log(11);</script></div>
        return (
            <div>  

            </div>
        );
    }
}

ReactDOM.render(
    <Aboutus param="1" />,
    document.getElementById('aboutus')
)