import knex from "knex";

const mysqlKnex = knex({
    client: "mysql",
    connection: {
        host: 'pizza-shop.prod.db',
        port: 3306,
        user: 'pizza_prod',
        password: 'pizza_prod',
        database: 'pizza_prod'
    }
});

export default mysqlKnex;